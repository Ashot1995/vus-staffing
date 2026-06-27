<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesBaseUrl;
use Illuminate\Http\Response;

class RobotsController extends Controller
{
    use ResolvesBaseUrl;

    public function index()
    {
        $baseUrl = rtrim($this->getBaseUrl(), '/');

        $content = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Disallow: /admin\n";
        $content .= "Disallow: /admin/\n";
        $content .= "Disallow: /dashboard\n";
        $content .= "Disallow: /profile\n";
        $content .= "Disallow: /profile/\n";
        $content .= "Disallow: /applications/\n";
        $content .= "Disallow: /login\n";
        $content .= "Disallow: /register\n";
        $content .= "Disallow: /password/\n";
        $content .= "Disallow: /forgot-password\n";
        $content .= "Disallow: /reset-password\n";
        $content .= "Disallow: /email/\n";
        $content .= "Disallow: /verify-email\n";
        $content .= "Disallow: /confirm-password\n";
        $content .= "Disallow: /logout\n";
        $content .= "Disallow: /search\n";
        $content .= "Disallow: /*?search=\n";
        $content .= "Disallow: /jobb/*/ansok\n";
        $content .= "\n";
        $content .= "Sitemap: {$baseUrl}/sitemap.xml\n";

        return response($content, 200)
            ->header('Content-Type', 'text/plain');
    }
}
