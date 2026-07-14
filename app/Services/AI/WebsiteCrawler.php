<?php

namespace App\Services\AI;

/* use Illuminate\Http\Client\ConnectionException; */
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class WebsiteCrawler
{
   public function crawl(string $url): array
{
    $normalizedUrl = $this->normalizeUrl($url);

    try {
        return $this->sendRequest(
            $normalizedUrl,
            true
        );
    } catch (Throwable $exception) {
        $message = strtolower(
            $exception->getMessage()
        );

        $isSslError =
            str_contains($message, 'curl error 60')
            || str_contains($message, 'ssl certificate problem')
            || str_contains($message, 'certificate has expired')
            || str_contains($message, 'unable to get local issuer certificate')
            || str_contains($message, 'self signed certificate');

        if (!$isSslError) {
            return [
                'success' => false,
                'url' => $normalizedUrl,
                'status_code' => null,
                'html' => null,
                'ssl_certificate_valid' => null,
                'ssl_warning' => null,
                'error' => $exception->getMessage(),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Retry only after an SSL certificate validation failure
        |--------------------------------------------------------------------------
        */

        try {
            $result = $this->sendRequest(
                $normalizedUrl,
                false
            );

            $result['ssl_certificate_valid'] = false;

            $result['ssl_warning'] =
                'The SSL certificate is expired, invalid, or untrusted.';

            return $result;
        } catch (Throwable $retryException) {
            return [
                'success' => false,
                'url' => $normalizedUrl,
                'status_code' => null,
                'html' => null,
                'ssl_certificate_valid' => false,
                'ssl_warning' =>
                    'The SSL certificate is expired, invalid, or untrusted.',
                'error' =>
                    'Website analysis failed after the SSL fallback: '
                    . $retryException->getMessage(),
            ];
        }
    }
}

private function sendRequest(
    string $url,
    bool $verifySsl = true
): array {
    $request = Http::timeout(25)
        ->connectTimeout(10)
        ->withOptions([
            'verify' => $verifySsl,
            'allow_redirects' => [
                'max' => 10,
                'strict' => false,
                'referer' => true,
                'track_redirects' => true,
            ],
        ])
        ->withHeaders([
            'User-Agent' =>
                'Mozilla/5.0 (Windows NT 10.0; Win64; x64) '
                . 'AppleWebKit/537.36 (KHTML, like Gecko) '
                . 'Chrome/120.0.0.0 Safari/537.36',

            'Accept' =>
                'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',

            'Accept-Language' =>
                'en-US,en;q=0.9',

            'Cache-Control' =>
                'no-cache',
        ]);

    $response = $request->get($url);

    if (!$response->successful()) {
        return [
            'success' => false,
            'url' => $url,
            'final_url' =>
                (string) $response->effectiveUri(),
            'status_code' =>
                $response->status(),
            'content_type' =>
                $response->header('Content-Type'),
            'server' =>
                $response->header('Server'),
            'html' => null,
            'ssl_certificate_valid' =>
                $verifySsl,
            'ssl_warning' =>
                $verifySsl
                    ? null
                    : 'The SSL certificate is expired, invalid, or untrusted.',
            'error' =>
                'Website returned HTTP status '
                . $response->status(),
        ];
    }

    $html = $response->body();

    return [
        'success' => true,
        'url' => $url,
        'final_url' =>
            (string) $response->effectiveUri(),
        'status_code' =>
            $response->status(),
        'content_type' =>
            $response->header('Content-Type'),
        'server' =>
            $response->header('Server'),
        'html' => $html,
        'html_length' =>
            strlen($html),
        'title' =>
            $this->extractTitle($html),
        'meta_description' =>
            $this->extractMetaDescription($html),
        'ssl_certificate_valid' =>
            $verifySsl,
        'ssl_warning' =>
            $verifySsl
                ? null
                : 'The SSL certificate is expired, invalid, or untrusted.',
    ];
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