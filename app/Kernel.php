<?php

use Overtrue\PHPLint\Linter;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass as BetterReflectionClass;
use Roave\BetterReflection\Reflector\ClassReflector;
use Roave\BetterReflection\SourceLocator\Type\FileIteratorSourceLocator;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Finder\Finder;

class Kernel
{
    /**
     * @return array
     */
    public function registerConsoleCommands()
    {
        $fileIterator = (new Finder())->files()->in($this->getConsoleCommandDirectory())->getIterator();

        $linter = new Linter($this->getRootDirectory());
        $lintingErrors = $linter->lint(iterator_to_array($fileIterator));
        if (!empty($lintingErrors)) {
            throw new LogicException('Registering of console commands failed due to linting errors.');
        }

        $astLocator = (new BetterReflection())->astLocator();
        $reflector = new ClassReflector(new FileIteratorSourceLocator($fileIterator, $astLocator));

        return array_filter(
            array_map(function(BetterReflectionClass $reflectionClass) {
                if ($reflectionClass->isAbstract() === false) {
                    $class_name = (string) $reflectionClass->getName();
                    return new $class_name;
                }
            }, $reflector->getAllClasses()), function($object) {
            return !is_null($object);
        });
    }

    /**
     * @return array
     */
    public function getEnvironmentalConfiguration()
    {
        return (new Dotenv())->parse(file_get_contents(
            $this->getRootDirectory() . DIRECTORY_SEPARATOR . '.env'));
    }

    /**
     * @return string
     */
    public function getRootDirectory()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . '..';
    }

    /**
     * @return string
     */
    public function getConsoleCommandDirectory()
    {
        return $this->getRootDirectory() . DIRECTORY_SEPARATOR . 'src' .
            DIRECTORY_SEPARATOR . 'Command' . DIRECTORY_SEPARATOR;
    }
}