<?php

namespace DZunke\FeatureFlagsBundle\Command;

use DZunke\FeatureFlagsBundle\Toggle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DebugFlagsCommand extends Command
{
    /**
     * @var Toggle
     */
    private $toggle;

    /**
     * @var Toggle\ConditionBag
     */
    private $conditionBag;

    public function __construct(Toggle $toggle, Toggle\ConditionBag $conditionBag)
    {
        parent::__construct();
        $this->toggle = $toggle;
        $this->conditionBag = $conditionBag;
    }

    protected function configure()
    {
        $this
            ->setName('dzunke:feature_flags:debug')
            ->setDescription('Debugging Feature Flags');
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('');
        $output->writeln('<fg=cyan>Debugging Feature Flags</fg=cyan>');
        $output->writeln('<fg=cyan>=======================</fg=cyan>');
        $output->writeln('');

        $output->writeln('<fg=cyan>Existing Flag-Conditions</fg=cyan>');
        $output->writeln('<fg=cyan>------------------------</fg=cyan>');
        $output->writeln('');

        $table = new Table($output);
        $table->setStyle('borderless');
        $table->setHeaders(['name', 'class']);
        foreach ($this->conditionBag as $name => $condition) {
            $table->addRow([$name, get_class($condition)]);
        }
        $table->render();

        $output->writeln('');

        $output->writeln('<fg=cyan>Configured Feature-Flags</fg=cyan>');
        $output->writeln('<fg=cyan>------------------------</fg=cyan>');
        $output->writeln('');

        $this->renderFlagsTable($output);

        $output->writeln('');

        return 0;
    }

    private function renderFlagsTable(OutputInterface $output)
    {
        $flags = $this->toggle->getFlags();
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
