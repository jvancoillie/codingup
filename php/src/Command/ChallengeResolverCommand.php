<?php

namespace App\Command;

use App\Challenge\AbstractChallengeResolver;
use App\Challenge\ChallengeInput;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ChallengeResolverCommand extends AbstractChallengeCommand
{
    protected static $defaultName = 'challenge:resolve';

    protected function configure()
    {
        $currentYear = (new \DateTime())->format('y');

        $this
            ->setDescription('Outputs the solutions of a Challenge for a given event')
            ->addOption('year', 'y', InputOption::VALUE_REQUIRED, 'the year of the event', $currentYear)
            ->addArgument('name', InputArgument::OPTIONAL, 'the Challenge name')
            ->addOption('test', null, InputOption::VALUE_NONE, 'If set, run with test input')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $year = $input->getOption('year');
        $name = $this->toCamelCase($input->getArgument('name'));

        $link = $this->getLink($name, $year);

        $isTest = $input->getOption('test');
        $options = ['mode' => $isTest ? AbstractChallengeResolver::TEST_MODE : AbstractChallengeResolver::PROD_MODE];
        $inputFileName = $isTest ? 'test.txt' : 'input.txt';
        $inputFilePath = sprintf('src/Challenge/Cup%d/%s/input/%s', $year, $name, $inputFileName);

        $data = file_get_contents($inputFilePath);
        $startTime = microtime(true);

        try {
            $args = [new ChallengeInput($data), $output, $options];

            $resolverInstance = $this->instantiateClass(sprintf('\\App\\Challenge\\Cup%d\\%s\\ChallengeResolver', $year, $name), $args);
            $callable = [$resolverInstance, 'main'];
        } catch (\Error $e) {
            $output->writeln(sprintf('<error>No class found for name %s of cup %d</error>', $name, $year));
            $output->writeln($e);

            return Command::FAILURE;
        }

        if (!\is_callable($callable)) {
            throw new \InvalidArgumentException(sprintf('the main method of class \\App\\Challenge\\Cup%d\\%s is not callable', $year, $name));
        }

        $output->writeln(sprintf('<info>=========  Challenge:  %1$s, MODE: %2$s ========= <info>', $name, $isTest ? 'Test' : 'Prod'));
        $output->writeln(sprintf('<info><href=%1$s>%1$s</><info>', $link));
        $output->writeln('');
        $output->writeln(sprintf('  <options=bold,underscore,conceal>Result</> <options=blink>:</> <fg=%s>%s</>', 'green', $callable()));

        $output->writeln([
            ' ',
            sprintf('<info>==========</info> <comment>Execution time: %01.4f</comment> <info>==========</info> ', microtime(true) - $startTime),
        ]);

        return Command::SUCCESS;
    }

    protected function instantiateClass(string $class, array $args): object
    {
        return new $class(...$args);
    }

    public function getChallengeDirectory(InputInterface $input): string
    {
        $year = $input->getOption('year');

        return sprintf('Cup%d', $year);
    }
}
