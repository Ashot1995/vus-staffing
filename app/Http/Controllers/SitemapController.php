<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesBaseUrl;
use App\Models\BlogPost;
use App\Models\Job;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    use ResolvesBaseUrl;

    public const CACHE_KEY = 'sitemap.xml';

    public function index()
    {
        $baseUrl = rtrim($this->getBaseUrl(), '/');

        $xml = Cache::remember(self::CACHE_KEY, 3600, function () use ($baseUrl) {
            return $this->build($baseUrl);
        });

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    private function build(string $baseUrl): string
    {
        $latestJob = Job::where('is_published', true)->latest('updated_at')->value('updated_at');
        $latestPost = BlogPost::published()->latest('updated_at')->value('updated_at');
        $jobsLastmod = optional($latestJob)->toIso8601String();
        $blogLastmod = optional($latestPost)->toIso8601String();

        $staticPages = [
            ['path' => '',                     'priority' => '1.0', 'changefreq' => 'weekly',  'lastmod' => $jobsLastmod ?: $blogLastmod],
            ['path' => '/jobb',                'priority' => '0.9', 'changefreq' => 'daily',   'lastmod' => $jobsLastmod],
            ['path' => '/spontanansok',        'priority' => '0.7', 'changefreq' => 'monthly', 'lastmod' => null],
            ['path' => '/for-arbetsgivare',    'priority' => '0.8', 'changefreq' => 'monthly', 'lastmod' => null],
            ['path' => '/om-oss',              'priority' => '0.8', 'changefreq' => 'monthly', 'lastmod' => null],
            ['path' => '/foretagsvarden',      'priority' => '0.8', 'changefreq' => 'monthly', 'lastmod' => null],
            ['path' => '/kandidatinformation', 'priority' => '0.8', 'changefreq' => 'monthly', 'lastmod' => null],
            ['path' => '/blog',                'priority' => '0.8', 'changefreq' => 'weekly',  'lastmod' => $blogLastmod],
            ['path' => '/kontakt',             'priority' => '0.7', 'changefreq' => 'monthly', 'lastmod' => null],
            ['path' => '/privacy',             'priority' => '0.3', 'changefreq' => 'yearly',  'lastmod' => null],
        ];

        $sitemap  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($staticPages as $page) {
            $sitemap .= "  <url>\n";
            $sitemap .= "    <loc>" . htmlspecialchars($baseUrl . $page['path']) . "</loc>\n";
            if (!empty($page['lastmod'])) {
                $sitemap .= "    <lastmod>" . htmlspecialchars($page['lastmod']) . "</lastmod>\n";
            }
            $sitemap .= "    <changefreq>{$page['changefreq']}</changefreq>\n";
            $sitemap .= "    <priority>{$page['priority']}</priority>\n";
            $sitemap .= "  </url>\n";
        }

        Job::where('is_published', true)
            ->select(['id', 'updated_at'])
            ->orderByDesc('updated_at')
            ->chunk(500, function ($jobs) use (&$sitemap, $baseUrl) {
                foreach ($jobs as $job) {
                    $sitemap .= "  <url>\n";
                    $sitemap .= "    <loc>" . htmlspecialchars($baseUrl . '/jobb/' . $job->id) . "</loc>\n";
                    $sitemap .= "    <lastmod>" . $job->updated_at->toIso8601String() . "</lastmod>\n";
                    $sitemap .= "    <changefreq>weekly</changefreq>\n";
                    $sitemap .= "    <priority>0.8</priority>\n";
                    $sitemap .= "  </url>\n";
                }
            });

        BlogPost::published()
            ->select(['slug', 'updated_at'])
            ->orderByDesc('updated_at')
            ->chunk(500, function ($posts) use (&$sitemap, $baseUrl) {
                foreach ($posts as $post) {
                    $sitemap .= "  <url>\n";
                    $sitemap .= "    <loc>" . htmlspecialchars($baseUrl . '/blog/' . $post->slug) . "</loc>\n";
                    $sitemap .= "    <lastmod>" . $post->updated_at->toIso8601String() . "</lastmod>\n";
                    $sitemap .= "    <changefreq>monthly</changefreq>\n";
                    $sitemap .= "    <priority>0.7</priority>\n";
                    $sitemap .= "  </url>\n";
                }
            });

        $sitemap .= '</urlset>';

        return $sitemap;
    }
}
