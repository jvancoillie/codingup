<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class AbstractChallengeCommand extends Command
{
    protected HttpClientInterface $client;

    protected Filesystem $filesystem;

    protected SymfonyStyle $io;

    public function __construct()
    {
        parent::__construct();
        $this->client = HttpClient::create();
        $this->filesystem = new Filesystem();
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (null !== $input->getArgument('name')) {
            return;
        }

        $finder = new Finder();
        $finder->depth('== 0')->directories()->in(__DIR__.'/../Challenge/'.$this->getChallengeDirectory($input));

        if (!$finder->hasResults()) {
            return;
        }

        $names = [];

        foreach ($finder as $directory) {
            $names[] = $directory->getRelativePathname();
        }

        $name = $this->io->choice(
            'Which Sample would you run ?',
            $names
        );

        $this->io->text(' > <info>Name</info>: '.$name);

        $input->setArgument('name', $name);
    }

    protected function toCamelCase($string)
    {
        $s = ucfirst($string);
        $bar = ucwords($s);

        return preg_replace('/\s+/', '', $bar);
    }

    protected function getLink($name)
    {
        $dotenv = new Dotenv();
        $dotenv->loadEnv(__DIR__.'/../../.env');

        $link = $_ENV['BASE_LINK'];
        $linkSuffix = $_ENV['DETAIL_LINK_SUFFIX'];

        return sprintf('%s%s%s', $link, $name, $linkSuffix);
    }

    protected function getDataLink($name)
    {
        $dotenv = new Dotenv();
        $dotenv->loadEnv(__DIR__.'/../../.env');

        $link = $_ENV['BASE_LINK'];
        $linkSuffix = $_ENV['INPUT_LINK_SUFFIX'];

        return sprintf('%s%s%s', $link, $name, $linkSuffix);
    }

    protected function getSessionid()
    {
        $dotenv = new Dotenv();
        // loads .env, .env.local, and .env.$APP_ENV.local or .env.$APP_ENV
        $dotenv->loadEnv(__DIR__.'/../../.env');

        return $_ENV['PYDEFIS_SESSSION_ID'];
    }

    abstract public function getChallengeDirectory(InputInterface $input): string;
}
