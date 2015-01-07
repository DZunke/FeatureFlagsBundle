<?php

namespace DZunke\FeatureFlagsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
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
        $output->writeln('');
        $output->writeln('<fg=cyan>Debugging Feature Flags</fg=cyan>');
        $output->writeln('<fg=cyan>=======================</fg=cyan>');
        $output->writeln('');

        $output->writeln('<fg=cyan>Existing Flag-Conditions</fg=cyan>');
        $output->writeln('<fg=cyan>------------------------</fg=cyan>');
        $output->writeln('');

        $conditions = $this->getContainer()->get('dz.feature_flags.conditions_bag');
        $table = new Table($output);
        $table->setStyle('borderless');
        $table->setHeaders(['name', 'class']);
        foreach ($conditions as $name => $condition) {
            $table->addRow([$name, get_class($condition)]);
        }
        $table->render();

        $output->writeln('');

        $output->writeln('<fg=cyan>Configured Feature-Flags</fg=cyan>');
        $output->writeln('<fg=cyan>------------------------</fg=cyan>');
        $output->writeln('');

        $this->renderFlagsTable($output);

        $output->writeln('');
    }

    private function renderFlagsTable(OutputInterface $output)
    {
        $flags = $this->getContainer()->get('dz.feature_flags.toggle')->getFlags();
        if (empty($flags)) {
            $output->writeln('<comment> ! [NOTE] there are no flags configured</comment>');
            return;
        }

        ksort($flags);

        $table = new Table($output);
        $table->setStyle('borderless');
        $table->setHeaders(['name', 'conditions']);

        foreach ($flags as $name => $flag) {
            $table->addRow([$name, implode(', ', array_keys($flag->getConfig()))]);
        }

        $table->render();
    }
}
