<?php
namespace Merchnt\HAProxyManager\Command;

use HAProxyApiClient\HAProxyApiClient;
use ReflectionException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseManagerCommand extends Command
{
    /**
     * @var HAProxyApiClient
     */
    private $haProxyApiClient;

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->haProxyApiClient = new HAProxyApiClient($this->getHaProxyProtocolConfiguration());
    }

    /**
     * @return array
     */
    private function getHaProxyProtocolConfiguration()
    {
        return [];
    }

    /**
     * @return HAProxyApiClient
     */
    public function getHAProxyApiClient()
    {
        return $this->haProxyApiClient;
    }
}