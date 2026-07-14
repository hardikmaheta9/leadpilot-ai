<?php

namespace App\Services\AI;

use DOMDocument;
use DOMXPath;

class WebsiteAnalyzer
{
    public function analyze(array $crawlData): array
    {
        if (
            empty($crawlData['success']) ||
            empty($crawlData['html'])
        ) {
            return [
                'success' => false,
                'error' => $crawlData['error'] ?? 'Website crawl failed.',
            ];
        }

        $html = $crawlData['html'];

        libxml_use_internal_errors(true);

        $dom = new DOMDocument();

        $loaded = $dom->loadHTML(
            mb_convert_encoding(
                $html,
                'HTML-ENTITIES',
                'UTF-8'
            )
        );

        libxml_clear_errors();

        if (!$loaded) {
            return [
                'success' => false,
                'error' => 'Unable to parse website HTML.',
            ];
        }

        $xpath = new DOMXPath($dom);

        return [
            'success' => true,

            'url' => $crawlData['final_url']
                ?? $crawlData['url']
                ?? null,

            'title' => $crawlData['title'] ?? null,

            'meta_description' =>
                $crawlData['meta_description'] ?? null,

            'headings' => [
                'h1' => $this->extractTexts($xpath, '//h1'),
                'h2' => $this->extractTexts($xpath, '//h2'),
                'h3' => $this->extractTexts($xpath, '//h3'),
            ],

            'links' => $this->extractLinks($xpath),

            'images' => $this->countNodes($xpath, '//img'),

            'forms' => $this->countNodes($xpath, '//form'),

            'scripts' => $this->countNodes($xpath, '//script'),

            'stylesheets' =>
                $this->countNodes(
                    $xpath,
                    '//link[@rel="stylesheet"]'
                ),

            'has_contact_page' =>
                $this->hasMatchingLink(
                    $xpath,
                    [
                        'contact',
                        'contact-us',
                        'get-in-touch',
                    ]
                ),

            'has_about_page' =>
                $this->hasMatchingLink(
                    $xpath,
                    [
                        'about',
                        'about-us',
                        'company',
                    ]
                ),

            'has_services_page' =>
                $this->hasMatchingLink(
                    $xpath,
                    [
                        'services',
                        'solutions',
                        'products',
                    ]
                ),

            'has_blog' =>
                $this->hasMatchingLink(
                    $xpath,
                    [
                        'blog',
                        'news',
                        'articles',
                    ]
                ),

            'has_privacy_policy' =>
                $this->hasMatchingLink(
                    $xpath,
                    [
                        'privacy',
                        'privacy-policy',
                    ]
                ),

            'has_viewport_meta' =>
                $this->hasViewportMeta($xpath),

            'has_ssl' =>
                str_starts_with(
                    $crawlData['final_url']
                        ?? $crawlData['url']
                        ?? '',
                    'https://'
                )
                && ($crawlData['ssl_certificate_valid'] ?? true),

            'word_count' =>
                $this->calculateWordCount($dom),

            'analyzed_at' => now(),
        ];
    }

    private function extractTexts(
        DOMXPath $xpath,
        string $query
    ): array {
        $items = [];

        $nodes = $xpath->query($query);

        if (!$nodes) {
            return [];
        }

        foreach ($nodes as $node) {
            $text = trim(
                preg_replace(
                    '/\s+/',
                    ' ',
                    $node->textContent
                )
            );

            if ($text !== '') {
                $items[] = $text;
            }
        }

        return array_values(
            array_unique($items)
        );
    }

    private function extractLinks(
        DOMXPath $xpath
    ): array {
        $links = [];

        $nodes = $xpath->query('//a[@href]');

        if (!$nodes) {
            return [];
        }

        foreach ($nodes as $node) {
            $href = trim(
                $node->getAttribute('href')
            );

            $text = trim(
                preg_replace(
                    '/\s+/',
                    ' ',
                    $node->textContent
                )
            );

            if ($href === '') {
                continue;
            }

            $links[] = [
                'text' => $text,
                'href' => $href,
            ];
        }

        return $links;
    }

    private function countNodes(
        DOMXPath $xpath,
        string $query
    ): int {
        $nodes = $xpath->query($query);

        return $nodes
            ? $nodes->length
            : 0;
    }

    private function hasMatchingLink(
        DOMXPath $xpath,
        array $keywords
    ): bool {
        $nodes = $xpath->query('//a[@href]');

        if (!$nodes) {
            return false;
        }

        foreach ($nodes as $node) {
            $href = strtolower(
                trim(
                    $node->getAttribute('href')
                )
            );

            $text = strtolower(
                trim($node->textContent)
            );

            foreach ($keywords as $keyword) {
                if (
                    str_contains($href, $keyword) ||
                    str_contains($text, $keyword)
                ) {
                    return true;
                }
            }
        }

        return false;
    }

    private function hasViewportMeta(
        DOMXPath $xpath
    ): bool {
        $nodes = $xpath->query(
            '//meta[
                translate(
                    @name,
                    "ABCDEFGHIJKLMNOPQRSTUVWXYZ",
                    "abcdefghijklmnopqrstuvwxyz"
                ) = "viewport"
            ]'
        );

        return $nodes && $nodes->length > 0;
    }

    private function calculateWordCount(
        DOMDocument $dom
    ): int {
        $body = $dom->getElementsByTagName('body')
            ->item(0);

        if (!$body) {
            return 0;
        }

        $text = trim(
            preg_replace(
                '/\s+/',
                ' ',
                $body->textContent
            )
        );

        if ($text === '') {
            return 0;
        }

        return str_word_count($text);
    }
}