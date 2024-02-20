<?php

namespace App\Command;

use App\Challenge\AbstractChallengeResolver;
use App\Challenge\ChallengeInput;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ChallengeSampleCommand extends AbstractChallengeCommand
{
    protected static $defaultName = 'challenge:sample';

    protected function configure(): void
    {
        $this
            ->setDescription('Outputs sample for a given name')
            ->addArgument('name', InputArgument::OPTIONAL, 'the sample name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $this->toCamelCase($input->getArgument('name'));

        $link = $this->getLink($name);

        $options = ['mode' => AbstractChallengeResolver::PROD_MODE];
        $inputFileName = 'input.txt';
        $inputFilePath = sprintf('src/Challenge/Sample/%s/input/%s', $name, $inputFileName);

        $data = file_get_contents($inputFilePath);
        $startTime = microtime(true);

        try {
            $args = [new ChallengeInput($data), $output, $options];

            $resolverInstance = $this->instantiateClass(sprintf('\\App\\Challenge\\Sample\\%s\\ChallengeResolver', $name), $args);
            $callable = [$resolverInstance, 'main'];
        } catch (\Error $e) {
            $output->writeln(sprintf('<error>No class found for sample name %s</error>', $name));
            $output->writeln($e);

            return Command::FAILURE;
        }

        if (!\is_callable($callable)) {
            throw new \InvalidArgumentException(sprintf('the main method of class \\App\\Challenge\\Sample\\%s is not callable', $name));
        }

        $output->writeln(sprintf('<info>=========  Challenge:  %1$s, MODE: %2$s ========= <info>', $name, 'Prod'));
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
        return 'Sample/';
    }
}
