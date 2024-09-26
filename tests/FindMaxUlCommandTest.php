<?php

namespace App\Tests\Command;

use App\Command\FindMaxUlCommand;
use App\Service\HtmlAnalyzer;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase;

class FindMaxUlCommandTest extends TestCase
{
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        $htmlAnalyzerMock = $this->createMock(HtmlAnalyzer::class);

        $command = new FindMaxUlCommand($htmlAnalyzerMock);

        $application = new Application();
        $application->add($command);

        $commandToTest = $application->find('app:find-max-ul');

        $this->commandTester = new CommandTester($commandToTest);
    }

    public function testExecuteWithValidHtml(): void
    {
        $htmlAnalyzerMock = $this->createMock(HtmlAnalyzer::class);
        $htmlAnalyzerMock->method('findLargestUl')->willReturn(3);

        $command = new FindMaxUlCommand($htmlAnalyzerMock);
        $application = new Application();
        $application->add($command);
        $commandToTest = $application->find('app:find-max-ul');
        $commandTester = new CommandTester($commandToTest);

        $commandTester->execute(['url' => 'https://example.com']);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('The largest <ul> has 3 <li> elements.', $output);
    }

    public function testExecuteWithEmptyHtml(): void
    {
        $htmlAnalyzerMock = $this->createMock(HtmlAnalyzer::class);
        $htmlAnalyzerMock->method('findLargestUl')
            ->willThrowException(new \InvalidArgumentException('HTML content cannot be empty.'));

        $command = new FindMaxUlCommand($htmlAnalyzerMock);
        $application = new Application();
        $application->add($command);
        $commandToTest = $application->find('app:find-max-ul');
        $commandTester = new CommandTester($commandToTest);

        $commandTester->execute(['url' => 'https://example.com']);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Error: HTML content cannot be empty.', $output);
    }

    public function testExecuteWithInvalidUrl(): void
    {
        $commandTester = $this->commandTester;

        $commandTester->execute(['url' => 'http://invalid-url']);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Unable to fetch webpage content', $output);
    }

    public function testExecuteWithNoUlInHtml(): void
    {
        $htmlAnalyzerMock = $this->createMock(HtmlAnalyzer::class);
        $htmlAnalyzerMock->method('findLargestUl')->willReturn(0);

        $command = new FindMaxUlCommand($htmlAnalyzerMock);
        $application = new Application();
        $application->add($command);
        $commandToTest = $application->find('app:find-max-ul');
        $commandTester = new CommandTester($commandToTest);

        $commandTester->execute(['url' => 'https://example.com']);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('No <ul> elements found.', $output);
    }
}
