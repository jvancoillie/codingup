<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::OPTIONAL, 'the Challenge name')
            ->addOption('directory', 'd', InputOption::VALUE_OPTIONAL, 'the year of the event')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
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
        sort($names);
        $name = $this->io->choice(
            'Which Sample would you run ?',
            $names
        );

        $this->io->text(' > <info>Name</info>: '.$name);

        $input->setArgument('name', $name);
    }

    protected function toCamelCase($string): string
    {
        $string = ucfirst($string);
        $string = str_replace('_', ' ', $string);
        $string = ucwords($string);

        return (string) preg_replace('/\s+/', '', $string);
    }

    protected function getLink($name): string
    {
        $dotenv = new Dotenv();
        $dotenv->loadEnv(__DIR__.'/../../.env');

        $link = $_ENV['BASE_LINK'];
        $linkSuffix = $_ENV['DETAIL_LINK_SUFFIX'];

        return sprintf('%s%s%s', $link, $name, $linkSuffix);
    }

    protected function getDataLink($name): string
    {
        $dotenv = new Dotenv();
        $dotenv->loadEnv(__DIR__.'/../../.env');

        $link = $_ENV['BASE_LINK'];
        $linkSuffix = $_ENV['INPUT_LINK_SUFFIX'];

        return sprintf('%s%s%s', $link, $name, $linkSuffix);
    }

    protected function getSessionId(): string
    {
        $dotenv = new Dotenv();
        // loads .env, .env.local, and .env.$APP_ENV.local or .env.$APP_ENV
        $dotenv->loadEnv(__DIR__.'/../../.env');

        return $_ENV['PYDEFIS_SESSSION_ID'];
    }

    public function getChallengeDirectory(InputInterface $input): string
    {
        $directory = $input->getOption('directory');

        if ($directory) {
            return $directory;
        }

        $dotenv = new Dotenv();
        $dotenv->loadEnv(__DIR__.'/../../.env');

        $link = $_ENV['BASE_LINK'];
        $parsedUrl = parse_url($link);
        $directory = basename($parsedUrl['path']);

        return ucfirst(!empty($directory) ? $directory : 'default');
    }
}
