<?php

namespace App\Tests\Service;

use App\Service\HtmlAnalyzer;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;
use Exception;

class HtmlAnalyzerTest extends TestCase
{
    public function testFindLargestUl(): void
    {
        $htmlAnalyzer = new HtmlAnalyzer();

        $html = <<<HTML
        <html>
            <body>
                <ul>
                    <li>Item 1</li>
                </ul>
                <ul>
                    <li>Item 1</li>
                    <li>Item 2</li>
                </ul>
                <ul>
                    <li>Item 1</li>
                    <li>Item 2</li>
                    <li>Item 3</li>
                </ul>
            </body>
        </html>
        HTML;

        $result = $htmlAnalyzer->findLargestUl($html);
        $this->assertEquals(3, $result);
    }

    public function testEmptyHtml(): void
    {
        $htmlAnalyzer = new HtmlAnalyzer();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("HTML content cannot be empty.");

        $htmlAnalyzer->findLargestUl('');
    }

    public function testNoUlInHtml(): void
    {
        $htmlAnalyzer = new HtmlAnalyzer();

        $html = <<<HTML
        <html>
            <body>
                <p>No list here</p>
            </body>
        </html>
        HTML;

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("No <ul> elements found in the provided HTML.");

        $htmlAnalyzer->findLargestUl($html);
    }

    public function testEmptyUl(): void
    {
        $htmlAnalyzer = new HtmlAnalyzer();

        $html = <<<HTML
        <html>
            <body>
                <ul></ul>
            </body>
        </html>
        HTML;

        $result = $htmlAnalyzer->findLargestUl($html);
        $this->assertEquals(0, $result);
    }

    public function testNestedUl(): void
    {
        $htmlAnalyzer = new HtmlAnalyzer();

        $html = <<<HTML
        <html>
            <body>
                <ul>
                    <li>Item 1</li>
                    <li>Item 2
                        <ul>
                            <li>Nested Item 1</li>
                            <li>Nested Item 2</li>
                        </ul>
                    </li>
                    <li>Item 3</li>
                </ul>
            </body>
        </html>
        HTML;

        $result = $htmlAnalyzer->findLargestUl($html);
        $this->assertEquals(3, $result);
    }

    public function testMalformedHtml(): void
    {
        $htmlAnalyzer = new HtmlAnalyzer();

        $html = <<<HTML
        <html>
            <body>
                <ul>
                    <li>Item 1
                    <li>Item 2</li>
                </ul>
            </body>
        </html>
        HTML;

        $result = $htmlAnalyzer->findLargestUl($html);
        $this->assertEquals(2, $result);
    }

    public function testMultipleUl(): void
    {
        $htmlAnalyzer = new HtmlAnalyzer();

        $html = <<<HTML
        <html>
            <body>
                <ul>
                    <li>Item 1</li>
                    <li>Item 2</li>
                </ul>
                <ul>
                    <li>Item 1</li>
                    <li>Item 2</li>
                    <li>Item 3</li>
                    <li>Item 4</li>
                </ul>
                <ul>
                    <li>Item 1</li>
                </ul>
            </body>
        </html>
        HTML;

        $result = $htmlAnalyzer->findLargestUl($html);
        $this->assertEquals(4, $result);
    }
}
