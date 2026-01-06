<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q', '');
        $query = trim($query);
        
        if (empty($query)) {
            return view('pages.search', [
                'query' => '',
                'results' => [],
                'totalResults' => 0,
            ]);
        }

        $results = [];
        
        // Search in pages
        $pageResults = $this->searchPages($query);
        $results = array_merge($results, $pageResults);
        
        // Search in blog posts
        $blogResults = $this->searchBlogPosts($query);
        $results = array_merge($results, $blogResults);
        
        // Search in jobs
        $jobResults = $this->searchJobs($query);
        $results = array_merge($results, $jobResults);
        
        // Sort results by relevance (simple scoring)
        usort($results, function ($a, $b) {
            return ($b['score'] ?? 0) <=> ($a['score'] ?? 0);
        });

        return view('pages.search', [
            'query' => $query,
            'results' => $results,
            'totalResults' => count($results),
        ]);
    }

    protected function searchPages(string $query): array
    {
        $results = [];
        $queryLower = strtolower($query);
        
        // Define searchable pages with their routes and content
        $pages = [
            [
                'title' => __('messages.nav.home'),
                'route' => 'home',
                'url' => route('home'),
                'content' => $this->getPageContent('home'),
            ],
            [
                'title' => __('messages.nav.about'),
                'route' => 'about',
                'url' => route('about'),
                'content' => $this->getPageContent('about'),
            ],
            [
                'title' => __('messages.nav.company_values'),
                'route' => 'company-values',
                'url' => route('company-values'),
                'content' => $this->getPageContent('company-values'),
            ],
            [
                'title' => __('messages.nav.for_employers'),
                'route' => 'for-employers',
                'url' => route('for-employers'),
                'content' => $this->getPageContent('for-employers'),
            ],
            [
                'title' => __('messages.nav.contact'),
                'route' => 'contact',
                'url' => route('contact'),
                'content' => $this->getPageContent('contact'),
            ],
            [
                'title' => __('messages.nav.blog'),
                'route' => 'blog.index',
                'url' => route('blog.index'),
                'content' => $this->getPageContent('blog'),
            ],
        ];

        foreach ($pages as $page) {
            $score = 0;
            $content = strtolower($page['content']);
            $title = strtolower($page['title']);
            
            // Check if query matches title (higher score)
            if (strpos($title, $queryLower) !== false) {
                $score += 100;
            }
            
            // Check if query matches content
            $matches = substr_count($content, $queryLower);
            $score += $matches * 10;
            
            if ($score > 0) {
                $results[] = [
                    'type' => 'page',
                    'title' => $page['title'],
                    'url' => $page['url'] . (strpos($page['url'], '?') === false ? '?' : '&') . 'search=' . urlencode($query),
                    'excerpt' => $this->generateExcerpt($page['content'], $query),
                    'score' => $score,
                ];
            }
        }

        return $results;
    }

    protected function searchBlogPosts(string $query): array
    {
        $results = [];
        $queryLower = strtolower($query);
        
        $posts = BlogPost::published()
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('content', 'LIKE', "%{$query}%")
                  ->orWhere('excerpt', 'LIKE', "%{$query}%");
            })
            ->get();

        foreach ($posts as $post) {
            $score = 0;
            $title = strtolower($post->title);
            $content = strtolower($post->content . ' ' . ($post->excerpt ?? ''));
            
            if (strpos($title, $queryLower) !== false) {
                $score += 100;
            }
            
            $matches = substr_count($content, $queryLower);
            $score += $matches * 10;
            
            $results[] = [
                'type' => 'blog',
                'title' => $post->title,
                'url' => route('blog.show', $post->slug) . '?search=' . urlencode($query),
                'excerpt' => $this->generateExcerpt($post->excerpt ?? strip_tags($post->content), $query),
                'score' => $score,
            ];
        }

        return $results;
    }

    protected function searchJobs(string $query): array
    {
        $results = [];
        $queryLower = strtolower($query);
        
        $jobs = Job::where('is_published', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('location', 'LIKE', "%{$query}%")
                  ->orWhere('requirements', 'LIKE', "%{$query}%")
                  ->orWhere('responsibilities', 'LIKE', "%{$query}%");
            })
            ->get();

        foreach ($jobs as $job) {
            $score = 0;
            $title = strtolower($job->title);
            $content = strtolower($job->description . ' ' . ($job->requirements ?? '') . ' ' . ($job->responsibilities ?? ''));
            
            if (strpos($title, $queryLower) !== false) {
                $score += 100;
            }
            
            if (strpos(strtolower($job->location), $queryLower) !== false) {
                $score += 50;
            }
            
            $matches = substr_count($content, $queryLower);
            $score += $matches * 10;
            
            $results[] = [
                'type' => 'job',
                'title' => $job->title . ' - ' . $job->location,
                'url' => route('jobs.show', $job) . '?search=' . urlencode($query),
                'excerpt' => $this->generateExcerpt($job->description, $query),
                'score' => $score,
            ];
        }

        return $results;
    }

    protected function getPageContent(string $page): string
    {
        // Get translation keys for the page
        $locale = app()->getLocale();
        $translations = Lang::get('messages', [], $locale);
        
        $content = '';
        
        // Define which translation keys belong to each page
        $pageKeys = [
            'home' => ['home', 'nav.home', 'about.welcome', 'about.vision', 'about.subtitle'],
            'about' => ['about', 'nav.about'],
            'company-values' => ['about.values', 'nav.company_values'],
            'for-employers' => ['employers', 'nav.for_employers'],
            'contact' => ['contact', 'nav.contact'],
            'blog' => ['blog', 'nav.blog'],
        ];
        
        $keys = $pageKeys[$page] ?? [];
        
        // Flatten translations to search in all matching keys
        $flatTranslations = $this->flattenArray($translations);
        
        foreach ($flatTranslations as $transKey => $transValue) {
            if (is_string($transValue)) {
                // Check if this translation key belongs to the page
                foreach ($keys as $key) {
                    if (strpos($transKey, $key) === 0 || $transKey === $key) {
                        $content .= ' ' . strip_tags($transValue);
                        break;
                    }
                }
            }
        }
        
        // Also add page-specific hardcoded content if needed
        if ($page === 'contact') {
            $content .= ' contact us email phone address';
        }
        
        return trim($content);
    }

    protected function flattenArray(array $array, string $prefix = ''): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $newKey = $prefix ? "{$prefix}.{$key}" : $key;
            if (is_array($value)) {
                $result = array_merge($result, $this->flattenArray($value, $newKey));
            } else {
                $result[$newKey] = $value;
            }
        }
        return $result;
    }

    protected function generateExcerpt(string $text, string $query, int $length = 200): string
    {
        $text = strip_tags($text);
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);
        
        if (strlen($text) <= $length) {
            return $text;
        }
        
        $queryLower = strtolower($query);
        $textLower = strtolower($text);
        
        // Try to find the query in the text
        $pos = strpos($textLower, $queryLower);
        
        if ($pos !== false) {
            // Start excerpt around the query
            $start = max(0, $pos - $length / 2);
            $excerpt = substr($text, $start, $length);
            
            if ($start > 0) {
                $excerpt = '...' . $excerpt;
            }
            
            if ($start + $length < strlen($text)) {
                $excerpt .= '...';
            }
        } else {
            // Just take the beginning
            $excerpt = substr($text, 0, $length) . '...';
        }
        
        return $excerpt;
    }
}
