<?php

namespace App\Challenge;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractChallengeResolver
{
    public const TEST_MODE = 'TEST';
    public const PROD_MODE = 'PROD';

    private array $options;

    public function __construct(private readonly ChallengeInput $input, private readonly OutputInterface $output, array $options = [])
    {
        $resolver = new OptionsResolver();
        $resolver->setDefault('mode', self::PROD_MODE);
        $resolver->setAllowedValues('mode', [self::PROD_MODE, self::TEST_MODE]);

        $this->options = $resolver->resolve($options);

        $this->initialize();
    }

    public function getInput(): ChallengeInput
    {
        return $this->input;
    }

    public function getOutput(): OutputInterface
    {
        return $this->output;
    }

    protected function isTestMode(): bool
    {
        return self::TEST_MODE === $this->options['mode'];
    }

    protected function getOptions(): array
    {
        return $this->options;
    }

    protected function initialize(): void
    {
        // void method, can be used before part1 & part2 is called
    }

    abstract public function main();
}
