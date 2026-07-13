<?php

namespace App\Services\AI;

class LeadScoringService
{
    public function calculate(
        array $analysis,
        array $technologies,
        array $contacts
    ): array {
        $score = 0;
        $reasons = [];

        if (!empty($analysis['has_ssl'])) {
            $score += 5;
        } else {
            $score += 15;
            $reasons[] = 'Website does not use HTTPS.';
        }

        if (!empty($analysis['has_viewport_meta'])) {
            $score += 5;
        } else {
            $score += 15;
            $reasons[] = 'Website may not be mobile-friendly.';
        }

        if (empty($analysis['meta_description'])) {
            $score += 10;
            $reasons[] = 'Meta description is missing.';
        }

        if (empty($analysis['headings']['h1'] ?? [])) {
            $score += 10;
            $reasons[] = 'Main H1 heading is missing.';
        }

        if (empty($analysis['has_contact_page'])) {
            $score += 10;
            $reasons[] = 'Contact page was not detected.';
        }

        if (empty($analysis['has_services_page'])) {
            $score += 10;
            $reasons[] = 'Services or products page was not detected.';
        }

        if (empty($analysis['has_blog'])) {
            $score += 5;
            $reasons[] = 'No blog or news section was detected.';
        }

        if (empty($contacts['emails'])) {
            $score += 10;
            $reasons[] = 'No public email address was detected.';
        }

        if (empty($contacts['phones'])) {
            $score += 5;
            $reasons[] = 'No phone number was detected.';
        }

        if (empty($contacts['social_links'])) {
            $score += 5;
            $reasons[] = 'No social media links were detected.';
        }

        if (empty($technologies['cms'])) {
            $score += 5;
            $reasons[] = 'Website CMS could not be detected.';
        }

        $score = min($score, 100);

        return [
            'lead_score' => $score,
            'lead_grade' => $this->grade($score),
            'reasons' => $reasons,
        ];
    }

    private function grade(int $score): string
    {
        return match (true) {
            $score >= 80 => 'A',
            $score >= 60 => 'B',
            $score >= 40 => 'C',
            $score >= 20 => 'D',
            default => 'E',
        };
    }
}