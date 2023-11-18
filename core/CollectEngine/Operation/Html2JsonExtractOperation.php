<?php

namespace CollectEngine\Operation;

use CollectEngine\Contract\ExtractorInput;
use CollectEngine\Contract\ExtractorOutput;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Filesystem\Filesystem;

readonly class Html2JsonExtractOperation implements ExtractOperation
{
    public function extract(ExtractorInput $input): ExtractorOutput
    {
        $html = file_get_contents($input->resources->export());

        $crawler = new Crawler($html);

        $result = [];

        foreach ($input->operation['fields'] as $field) {
            $value = $this->extractValue($crawler, $field);

            // todo converter
            $result[$field['name']] = $value;
        }

        // todo
        $tempnam = (new Filesystem())->tempnam(sys_get_temp_dir(), '');
        file_put_contents($tempnam, json_encode($result, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        return new ExtractorOutput(
            resources: $input->resources->import(
                key: 'extract',
                filePath: $tempnam,
            ),
        );
    }

    private function extractValue(Crawler $crawler, array $field): string|array|null
    {
        $value = null;

        $targetCrawler = $crawler;
        if (isset($field['rows_selector'])) {
            $targetCrawler = $crawler->filterXPath($field['rows_selector']);
        }

        if ($field['type'] === 'text') {
            $value = $targetCrawler->filterXPath($field['selector'])->text();
        } elseif ($field['type'] === 'list') {
            if (is_string($field['selector'])) {
                // ['a', 'b', 'c'] 形式
                $value = $targetCrawler->filterXPath($field['selector'])->each(function (Crawler $node) {
                    return $node->text();
                });
            } elseif (is_array($field['selector'])) {
                // [['k' => 'v', ...], ...] 形式
                $value = $targetCrawler->each(function (Crawler $node) use ($field) {
                    $item = [];
                    foreach ($field['selector'] as $selector) {
                        $item[$selector['name']] = $this->extractValue($node, $selector);
                    }
                    return $item;
                });
            }
        } elseif ($field['type'] === 'object') {
            // [['k' => 'v', ...], ...] 形式
            $children = [];
            foreach ($field['selector'] as $selector) {
                $children[$selector['name']] = $this->extractValue($targetCrawler, $selector);
            }
            $value = $children;
        }

        return $value;
    }
}
