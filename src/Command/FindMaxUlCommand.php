<?php

namespace App\Command;

use App\Service\HtmlAnalyzer;
use Exception;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FindMaxUlCommand extends Command
{
    private HtmlAnalyzer $htmlAnalyzer;

    public function __construct(HtmlAnalyzer $htmlAnalyzer)
    {
        parent::__construct();
        $this->htmlAnalyzer = $htmlAnalyzer;
    }

    protected function configure(): void
    {
        $this
            ->setName('app:find-max-ul')
            ->setDescription('description')
            ->addArgument('url');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $url = $input->getArgument('url');

        $html = @file_get_contents($url);

        if ($html === false) {
            $output->writeln('<error>Unable to fetch webpage content</error>');
            return Command::FAILURE;
        }

        $maxLiCount = 0;

        try {
            $maxLiCount = $this->htmlAnalyzer->findLargestUl($html);
            if ($maxLiCount > 0) {
                $output->writeln("The largest <ul> has {$maxLiCount} <li> elements.");
            } else {
                $output->writeln('<info>No <ul> elements found.</info>');
            }
        } catch (InvalidArgumentException $e) {
            $output->writeln('<error>Error: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        } catch (Exception $e) {
            $output->writeln('<error>Error: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
