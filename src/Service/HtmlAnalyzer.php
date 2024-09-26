<?php

namespace App\Service;

use DOMDocument;
use Exception;
use InvalidArgumentException;

class HtmlAnalyzer
{
    public function findLargestUl(string $html): int
    {

        if (empty($html)) {
            throw new InvalidArgumentException("HTML content cannot be empty.");
        }

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);

        try {
            if (!$dom->loadHTML($html)) {
                throw new Exception("Failed to load HTML.");
            }

            $ulElements = $dom->getElementsByTagName('ul');

            if ($ulElements->length === 0) {
                throw new Exception("No <ul> elements found in the provided HTML.");
            }
            $maxCount = 0;
            foreach ($ulElements as $ul) {
                $childCount = 0;
                foreach ($ul->childNodes as $child) {
                    if ($child->nodeName === 'li') {
                        $childCount++;
                    }
                }
                if ($childCount > $maxCount) {
                    $maxCount = $childCount;
                }
            }

            return $maxCount;
        } catch (Exception $e) {
            throw new Exception("Error while processing HTML: " . $e->getMessage());
        } finally {
            libxml_clear_errors();
        }
    }
}
