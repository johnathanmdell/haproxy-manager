<?php

use Symfony\Component\Console\Application;

require 'vendor/autoload.php';

/**
 * @return Application
 */
$application = function () {
    $application = new Application();
    $application->addCommands((new Kernel())->registerConsoleCommands());
    return $application;
};