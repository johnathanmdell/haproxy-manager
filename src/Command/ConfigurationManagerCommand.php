<?php
namespace Merchnt\HAProxyManager\Command;

use HAProxyApiClient\Manager\Configuration;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class ConfigurationManagerCommand extends BaseManagerCommand
{
    protected function configure()
    {
        $this->setName('manage:configuration');
        $this->setDescription('Console command to manage the configuration of HAProxy');
        $this->addArgument('action', InputArgument::REQUIRED, 'Action to execute on the configuration', null);
        $this->addOption('section', null, InputOption::VALUE_OPTIONAL, 'Section to execute the action on', null);
        $this->addOption('key', null, InputOption::VALUE_OPTIONAL, 'Section key to execute the action on', null);
        $this->addOption('value', null, InputOption::VALUE_OPTIONAL, 'Section value to execute the action on', null);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getHAProxyApiClient()->run(Configuration::class, $input->getArgument('action'), [
            $input->getOption('section'),
            $input->getOption('key'),
            $input->getOption('value')
        ]);
    }
}