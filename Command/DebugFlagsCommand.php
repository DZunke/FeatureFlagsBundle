<?php

namespace DZunke\FeatureFlagsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DebugFlagsCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('dzunke:feature_flags:debug')
            ->setDescription('Debugging Feature Flags');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $toggler = $this->getContainer()->get('dz.feature_flags.toggle');

        $flags = $toggler->getFlags();

        $output->writeln('<info>DefaultFeature: </info>' . ($toggler->isActive('DefaultFeature') ? 'true' : 'false'));

        foreach ($flags as $name => $flag) {

            $output->writeln('<info>' . $name . ': </info>' . ($toggler->isActive($name) ? 'true' : 'false'));

        }


    }

}
