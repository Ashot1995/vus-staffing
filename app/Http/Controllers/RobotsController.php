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
        $content .= "Allow: /\n\n";
        $content .= "Sitemap: {$baseUrl}/sitemap.xml\n";

        return response($content, 200)
            ->header('Content-Type', 'text/plain');
    }
}
