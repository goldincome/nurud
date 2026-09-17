<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap XML to public/sitemap.xml';

    public function handle(): int
    {
        $base = config('app.url');
        if (!str_starts_with($base, 'https://')) {
            $this->warn('APP_URL is not HTTPS. Using https://nurud.com as base URL.');
            $base = 'https://nurud.com';
        }

        $now = now()->toIso8601String();

        $urls = $this->getStaticUrls($base);

        $urls = array_merge($urls, $this->getDestinationUrls($base));

        $urls = array_merge($urls, $this->getFlightRouteUrls($base));

        $this->writeSitemap($urls, $now);

        $this->info('Sitemap written to public/sitemap.xml');

        return Command::SUCCESS;
    }

    private function getStaticUrls(string $base): array
    {
        $staticPaths = [
            ['uri' => '/', 'changefreq' => 'weekly', 'priority' => 1.0],
            ['uri' => '/login', 'changefreq' => 'monthly', 'priority' => 0.5],
            ['uri' => '/register', 'changefreq' => 'monthly', 'priority' => 0.5],
            ['uri' => '/about', 'changefreq' => 'yearly', 'priority' => 0.7],
            ['uri' => '/services', 'changefreq' => 'monthly', 'priority' => 0.9],
            ['uri' => '/services/nin-bvn-enrollment-uk', 'changefreq' => 'monthly', 'priority' => 0.9],
            ['uri' => '/services/nigerian-passport-renewal-london', 'changefreq' => 'monthly', 'priority' => 0.9],
            ['uri' => '/services/tin-registration-uk', 'changefreq' => 'monthly', 'priority' => 0.9],
            ['uri' => '/services/book-now-pay-later-flights', 'changefreq' => 'monthly', 'priority' => 0.9],
            ['uri' => '/services/travel-insurance-nigeria', 'changefreq' => 'monthly', 'priority' => 0.9],
            ['uri' => '/contact', 'changefreq' => 'yearly', 'priority' => 0.7],
            ['uri' => '/faq', 'changefreq' => 'yearly', 'priority' => 0.7],
            ['uri' => '/travel-guides', 'changefreq' => 'monthly', 'priority' => 0.9],
            ['uri' => '/travel-guides/nigeria-entry-visa-requirements-uk', 'changefreq' => 'monthly', 'priority' => 0.9],
            ['uri' => '/travel-guides/lagos-murtala-muhammed-airport-guide', 'changefreq' => 'monthly', 'priority' => 0.9],
            ['uri' => '/travel-guides/how-to-renew-nigerian-passport-from-uk', 'changefreq' => 'monthly', 'priority' => 0.9],
            ['uri' => '/travel-guides/nin-bvn-enrolment-centres-uk', 'changefreq' => 'monthly', 'priority' => 0.9],
            ['uri' => '/travel-guides/best-time-to-fly-london-to-lagos', 'changefreq' => 'monthly', 'priority' => 0.9],
            ['uri' => '/travel-agent-woolwich-london', 'changefreq' => 'monthly', 'priority' => 0.8],
            ['uri' => '/privacy', 'changefreq' => 'yearly', 'priority' => 0.3],
            ['uri' => '/terms', 'changefreq' => 'yearly', 'priority' => 0.3],
        ];

        return array_map(fn ($item) => [
            'loc' => rtrim($base, '/') . $item['uri'],
            'changefreq' => $item['changefreq'],
            'priority' => $item['priority'],
        ], $staticPaths);
    }

    private function getDestinationUrls(string $base): array
    {
        try {
            $class = 'App\\Data\\CountryDestinations';
            if (!class_exists($class)) {
                return [];
            }

            $slugs = array_keys($class::all());
        } catch (\Throwable) {
            return [];
        }

        $urls = [
            ['uri' => '/destinations', 'changefreq' => 'monthly', 'priority' => 0.9],
        ];

        foreach ($slugs as $slug) {
            $urls[] = ['uri' => '/destinations/' . $slug, 'changefreq' => 'monthly', 'priority' => 0.9];
        }

        return array_map(fn ($item) => [
            'loc' => rtrim($base, '/') . $item['uri'],
            'changefreq' => $item['changefreq'],
            'priority' => $item['priority'],
        ], $urls);
    }

    private function getFlightRouteUrls(string $base): array
    {
        try {
            $class = 'App\\Models\\FlightRoute';
            if (!class_exists($class)) {
                return [];
            }

            $slugs = $class::where('is_indexable', true)
                ->pluck('slug')
                ->all();
        } catch (\Throwable) {
            return [];
        }

        return array_map(fn ($slug) => [
            'loc' => rtrim($base, '/') . '/flights/' . $slug,
            'changefreq' => 'weekly',
            'priority' => 0.8,
        ], $slugs);
    }

    private function writeSitemap(array $urls, string $lastmod): void
    {
        $path = public_path('sitemap.xml');
        $xmlWriter = new \XMLWriter();
        $xmlWriter->openUri($path);
        $xmlWriter->startDocument('1.0', 'UTF-8');
        $xmlWriter->startElement('urlset');
        $xmlWriter->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        foreach ($urls as $url) {
            $xmlWriter->startElement('url');
            $xmlWriter->writeElement('loc', $url['loc']);
            $xmlWriter->writeElement('lastmod', $lastmod);
            $xmlWriter->writeElement('changefreq', $url['changefreq']);
            $xmlWriter->writeElement('priority', number_format($url['priority'], 1));
            $xmlWriter->endElement();
        }

        $xmlWriter->endElement();
        $xmlWriter->endDocument();
        $xmlWriter->flush();
    }
}