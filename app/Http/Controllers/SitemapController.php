<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesBaseUrl;
use App\Models\BlogPost;
use App\Models\Job;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    use ResolvesBaseUrl;

    public function index()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        $baseUrl = rtrim($this->getBaseUrl(), '/');
        $lastmod = date('Y-m-d');

        // Static pages
        $staticPages = [
            ['url' => $baseUrl, 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['url' => $baseUrl . '/om-oss', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['url' => $baseUrl . '/for-arbetsgivare', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['url' => $baseUrl . '/foretagsvarden', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['url' => $baseUrl . '/kandidatinformation', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['url' => $baseUrl . '/blog', 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['url' => $baseUrl . '/jobb', 'priority' => '0.9', 'changefreq' => 'daily'],
            ['url' => $baseUrl . '/spontanansok', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => $baseUrl . '/kontakt', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => $baseUrl . '/privacy', 'priority' => '0.3', 'changefreq' => 'yearly'],
        ];
        
        foreach ($staticPages as $page) {
            $sitemap .= "  <url>\n";
            $sitemap .= "    <loc>" . htmlspecialchars($page['url']) . "</loc>\n";
            $sitemap .= "    <lastmod>{$lastmod}</lastmod>\n";
            $sitemap .= "    <changefreq>{$page['changefreq']}</changefreq>\n";
            $sitemap .= "    <priority>{$page['priority']}</priority>\n";
            $sitemap .= "  </url>\n";
        }
        
        // Job listings
        $jobs = Job::where('is_published', true)->get();
        foreach ($jobs as $job) {
            $sitemap .= "  <url>\n";
            $sitemap .= "    <loc>" . htmlspecialchars($baseUrl . '/jobb/' . $job->id) . "</loc>\n";
            $sitemap .= "    <lastmod>" . $job->updated_at->format('Y-m-d') . "</lastmod>\n";
            $sitemap .= "    <changefreq>weekly</changefreq>\n";
            $sitemap .= "    <priority>0.8</priority>\n";
            $sitemap .= "  </url>\n";
        }

        // Blog posts
        $posts = BlogPost::published()->get();
        foreach ($posts as $post) {
            $sitemap .= "  <url>\n";
            $sitemap .= "    <loc>" . htmlspecialchars($baseUrl . '/blog/' . $post->slug) . "</loc>\n";
            $sitemap .= "    <lastmod>" . $post->updated_at->format('Y-m-d') . "</lastmod>\n";
            $sitemap .= "    <changefreq>monthly</changefreq>\n";
            $sitemap .= "    <priority>0.7</priority>\n";
            $sitemap .= "  </url>\n";
        }

        $sitemap .= '</urlset>';
        
        return response($sitemap, 200)
            ->header('Content-Type', 'application/xml');
    }
}



