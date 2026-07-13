<?php

namespace App\Services\AI;

class ContactExtractor
{
    public function extract(array $crawlData): array
    {
        $html = $crawlData['html'] ?? '';

        if ($html === '') {
            return [
                'emails' => [],
                'phones' => [],
                'social_links' => [],
            ];
        }

        return [
            'emails' => $this->extractEmails($html),
            'phones' => $this->extractPhones($html),
            'social_links' => $this->extractSocialLinks($html),
        ];
    }

    private function extractEmails(string $html): array
    {
        $emails = [];

        preg_match_all(
            '/[A-Z0-9._%+\-]+@[A-Z0-9.\-]+\.[A-Z]{2,}/i',
            $html,
            $matches
        );

        foreach ($matches[0] ?? [] as $email) {
            $email = strtolower(trim($email));

            if (
                str_contains($email, 'example.com') ||
                str_contains($email, 'domain.com') ||
                str_contains($email, 'email.com')
            ) {
                continue;
            }

            $emails[] = $email;
        }

        return array_values(
            array_unique($emails)
        );
    }

    private function extractPhones(string $html): array
    {
        $phones = [];

        $cleanText = html_entity_decode(
            strip_tags($html),
            ENT_QUOTES | ENT_HTML5,
            'UTF-8'
        );

        preg_match_all(
            '/(?:\+?\d{1,3}[\s\-.()]*)?(?:\d[\s\-.()]*){7,15}/',
            $cleanText,
            $matches
        );

        foreach ($matches[0] ?? [] as $phone) {
            $phone = trim(
                preg_replace('/\s+/', ' ', $phone)
            );

            $digits = preg_replace('/\D+/', '', $phone);

            if (
                strlen($digits) < 7 ||
                strlen($digits) > 15
            ) {
                continue;
            }

            $phones[] = $phone;
        }

        return array_values(
            array_unique($phones)
        );
    }

    private function extractSocialLinks(string $html): array
    {
        $socialLinks = [];

        preg_match_all(
            '/href=["\']([^"\']+)["\']/i',
            $html,
            $matches
        );

        $platforms = [
            'linkedin' => 'linkedin.com',
            'facebook' => 'facebook.com',
            'instagram' => 'instagram.com',
            'twitter' => 'twitter.com',
            'x' => 'x.com',
            'youtube' => 'youtube.com',
            'pinterest' => 'pinterest.com',
        ];

        foreach ($matches[1] ?? [] as $url) {
            $url = html_entity_decode(
                trim($url),
                ENT_QUOTES | ENT_HTML5,
                'UTF-8'
            );

            foreach ($platforms as $platform => $domain) {
                if (str_contains(strtolower($url), $domain)) {
                    $socialLinks[$platform] = $url;
                }
            }
        }

        return $socialLinks;
    }
}