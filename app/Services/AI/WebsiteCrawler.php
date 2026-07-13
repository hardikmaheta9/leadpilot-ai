<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class WebsiteCrawler
{
    public function crawl(string $url): array
    {
        $normalizedUrl = $this->normalizeUrl($url);

        try {
            $response = Http::timeout(20)
                ->retry(2, 500)
                ->withHeaders([
                    'User-Agent' => 'LeadPilotAI/1.0',
                    'Accept' => 'text/html,application/xhtml+xml',
                ])
                ->get($normalizedUrl);

            if (!$response->successful()) {
                return [
                    'success' => false,
                    'url' => $normalizedUrl,
                    'status_code' => $response->status(),
                    'html' => null,
                    'error' => 'Website returned HTTP status ' . $response->status(),
                ];
            }

            $html = $response->body();

            return [
                'success' => true,
                'url' => $normalizedUrl,
                'final_url' => (string) $response->effectiveUri(),
                'status_code' => $response->status(),
                'content_type' => $response->header('Content-Type'),
                'html' => $html,
                'html_length' => strlen($html),
                'title' => $this->extractTitle($html),
                'meta_description' => $this->extractMetaDescription($html),
            ];
        } catch (Throwable $exception) {
            return [
                'success' => false,
                'url' => $normalizedUrl,
                'status_code' => null,
                'html' => null,
                'error' => $exception->getMessage(),
            ];
        }
    }

    private function normalizeUrl(string $url): string
    {
        $url = trim($url);

        if (!Str::startsWith($url, ['http://', 'https://'])) {
            $url = 'https://' . $url;
        }

        return $url;
    }

    private function extractTitle(string $html): ?string
    {
        if (
            preg_match(
                '/<title[^>]*>(.*?)<\/title>/is',
                $html,
                $matches
            )
        ) {
            return trim(
                html_entity_decode(
                    strip_tags($matches[1]),
                    ENT_QUOTES | ENT_HTML5,
                    'UTF-8'
                )
            );
        }

        return null;
    }

    private function extractMetaDescription(string $html): ?string
    {
        if (
            preg_match(
                '/<meta[^>]+name=["\']description["\'][^>]+content=["\'](.*?)["\'][^>]*>/is',
                $html,
                $matches
            )
        ) {
            return trim(
                html_entity_decode(
                    $matches[1],
                    ENT_QUOTES | ENT_HTML5,
                    'UTF-8'
                )
            );
        }

        if (
            preg_match(
                '/<meta[^>]+content=["\'](.*?)["\'][^>]+name=["\']description["\'][^>]*>/is',
                $html,
                $matches
            )
        ) {
            return trim(
                html_entity_decode(
                    $matches[1],
                    ENT_QUOTES | ENT_HTML5,
                    'UTF-8'
                )
            );
        }

        return null;
    }
}