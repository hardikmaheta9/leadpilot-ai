<?php

namespace App\Services\AI;

class TechnologyDetector
{
    public function detect(array $crawlData): array
    {
        $html = strtolower($crawlData['html'] ?? '');

        if ($html === '') {
            return [
                'cms' => null,
                'frameworks' => [],
                'libraries' => [],
                'analytics' => [],
                'server' => null,
                'technologies' => [],
            ];
        }

        $cms = $this->detectCms($html);
        $frameworks = $this->detectFrameworks($html);
        $libraries = $this->detectLibraries($html);
        $analytics = $this->detectAnalytics($html);

        $technologies = array_values(
            array_unique(
                array_filter([
                    $cms,
                    ...$frameworks,
                    ...$libraries,
                    ...$analytics,
                ])
            )
        );

        return [
            'cms' => $cms,
            'frameworks' => $frameworks,
            'libraries' => $libraries,
            'analytics' => $analytics,
            'server' => $crawlData['server'] ?? null,
            'technologies' => $technologies,
        ];
    }

    private function detectCms(string $html): ?string
    {
        $patterns = [
            'WordPress' => [
                'wp-content',
                'wp-includes',
                'wordpress',
            ],

            'Shopify' => [
                'cdn.shopify.com',
                'shopify.theme',
                'shopify-section',
            ],

            'Wix' => [
                'wixstatic.com',
                'wix.com',
            ],

            'Squarespace' => [
                'static1.squarespace.com',
                'squarespace',
            ],

            'Drupal' => [
                'drupal-settings-json',
                'sites/default/files',
            ],

            'Joomla' => [
                '/media/system/js/',
                'joomla',
            ],

            'Magento' => [
                'mage/cookies',
                'magento',
                'static/version',
            ],

            'Webflow' => [
                'webflow.js',
                'webflow.com',
            ],
        ];

        foreach ($patterns as $name => $needles) {
            foreach ($needles as $needle) {
                if (str_contains($html, $needle)) {
                    return $name;
                }
            }
        }

        return null;
    }

    private function detectFrameworks(string $html): array
    {
        $detected = [];

        $patterns = [
            'Laravel' => [
                'laravel_session',
                'csrf-token',
            ],

            'React' => [
                'react',
                '__next_data__',
            ],

            'Next.js' => [
                '__next_data__',
                '/_next/',
            ],

            'Vue.js' => [
                'vue',
                'data-v-',
            ],

            'Nuxt.js' => [
                '__nuxt__',
                '/_nuxt/',
            ],

            'Angular' => [
                'ng-version',
                'angular',
            ],

            'Bootstrap' => [
                'bootstrap.min.css',
                'bootstrap.bundle',
            ],

            'Tailwind CSS' => [
                'tailwind',
            ],
        ];

        foreach ($patterns as $name => $needles) {
            foreach ($needles as $needle) {
                if (str_contains($html, $needle)) {
                    $detected[] = $name;
                    break;
                }
            }
        }

        return array_values(array_unique($detected));
    }

    private function detectLibraries(string $html): array
    {
        $detected = [];

        $patterns = [
            'jQuery' => [
                'jquery.min.js',
                'jquery.js',
            ],

            'Alpine.js' => [
                'alpinejs',
                'x-data=',
            ],

            'Swiper' => [
                'swiper-bundle',
                'swiper.min',
            ],

            'Slick Slider' => [
                'slick.min.js',
                'slick-carousel',
            ],

            'Font Awesome' => [
                'font-awesome',
                'fontawesome',
            ],
        ];

        foreach ($patterns as $name => $needles) {
            foreach ($needles as $needle) {
                if (str_contains($html, $needle)) {
                    $detected[] = $name;
                    break;
                }
            }
        }

        return array_values(array_unique($detected));
    }

    private function detectAnalytics(string $html): array
    {
        $detected = [];

        $patterns = [
            'Google Analytics' => [
                'google-analytics.com',
                'googletagmanager.com/gtag',
                'gtag(',
            ],

            'Google Tag Manager' => [
                'googletagmanager.com/gtm.js',
            ],

            'Meta Pixel' => [
                'connect.facebook.net',
                'fbq(',
            ],

            'Hotjar' => [
                'static.hotjar.com',
                'hj(',
            ],
        ];

        foreach ($patterns as $name => $needles) {
            foreach ($needles as $needle) {
                if (str_contains($html, $needle)) {
                    $detected[] = $name;
                    break;
                }
            }
        }

        return array_values(array_unique($detected));
    }
}