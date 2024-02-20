<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class ChallengeMakerCommand extends AbstractChallengeCommand
{
    protected static $defaultName = 'challenge:make';

    protected function configure()
    {
        $currentYear = (new \DateTime())->format('y');

        $this
            ->setDescription('Create the input data and structure for a given Challenge name')
            ->addArgument('name', InputArgument::OPTIONAL, 'the Challenge name')
            ->addOption('year', 'y', InputOption::VALUE_REQUIRED, 'the year of the event', $currentYear)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $year = $input->getOption('year');

        $name = $this->toCamelCase($input->getArgument('name'));

        $link = $this->getLink($name, $year);

        $folderPath = sprintf('src/Challenge/Cup%d/%s', $year, $name);
        $namespace = sprintf("App\Challenge\Cup%d\%s", $year, $name);

        $inputFilePath = sprintf('%s/input/input.txt', $folderPath);
        $testFilePath = sprintf('%s/input/test.txt', $folderPath);
        $resolverFilePath = sprintf('%s/ChallengeResolver.php', $folderPath);

        if ($this->filesystem->exists($resolverFilePath)) {
            $output->writeln(
                sprintf('<comment> Unable to create Challenge "%s" Resolver Class, already exist ! </comment>', $name)
            );

            return Command::FAILURE;
        }

        $output->writeln(sprintf('<info>Create Challenge "%s" Resolver Class</info>', $name));
        $this->filesystem->dumpFile(
            $resolverFilePath,
            $this->parseTemplate(
                __DIR__.'/../Resources/skeleton/ChallengeResolver.tpl.php',
                [
                    'namespace' => $namespace,
                    'challengeLink' => $link,
                ]
            )
        );

        $output->writeln(sprintf('<info>Create Challenge "%s" data input files</info>', $name));

        $this->filesystem->dumpFile($inputFilePath, '');
        $this->filesystem->dumpFile($testFilePath, '');

        $output->writeln(sprintf('<info>--- Retrieve data for Challenge:  %1$s --- <info>', $name));

        $sessionId = $this->getSessionid();
        $options = [];

        if ($sessionId) {
            $output->writeln(
                '<info>--- With PYDEFIS_SESSSION_ID ---<info>'
            );
            $options = [
                'headers' => ['Cookie' => 'session='.$sessionId],
            ];
        }
        try {
            $inputDataLink = $this->getDataLink($year, $name);
            $output->writeln(sprintf('<info>--- %s --- <info>', $inputDataLink));
            $response = $this->client->request(
                'GET',
                $inputDataLink,
                $options
            );

            if (200 === $response->getStatusCode()) {
                $crawler = new Crawler($response->getContent());
                $content = $crawler
                    ->filter('body > pre');

                $this->filesystem->dumpFile($inputFilePath, trim($content->html()));
            }
        } catch (\Error $e) {
            $output->writeln(
                sprintf('<error>Error when retrieve input data for Challenge "%s"</error>', $name)
            );

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (null !== $input->getArgument('name')) {
            return;
        }

        $this->io->title('Add Challenge Command Interactive Wizard');

        // Ask for the Challenge name if it's not defined
        $name = $input->getArgument('name');

        $name = $this->io->ask('Challenge name');
        $input->setArgument('name', $name);
    }

    public function parseTemplate(string $templatePath, array $parameters): string
    {
        ob_start();
        extract($parameters, EXTR_SKIP);
        include $templatePath;

        return ob_get_clean();
    }

    public function getChallengeDirectory(InputInterface $input): string
    {
        $year = $input->getOption('year');

        return sprintf('Cup%d', $year);
    }
}
