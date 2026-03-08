<?php

namespace App\Http\Controllers\Concerns;

trait ResolvesBaseUrl
{
    /**
     * Get the base URL for absolute URLs (sitemap, robots, etc.).
     * Uses request context when available to ensure correct domain regardless of APP_URL.
     * Falls back to config or production domain when request has localhost.
     */
    protected function getBaseUrl(): string
    {
        $url = request()->getSchemeAndHttpHost();

        if (str_contains($url, 'localhost') || str_contains($url, '127.0.0.1')) {
            $configUrl = rtrim(config('app.url', ''), '/');
            if (! empty($configUrl) && ! str_contains($configUrl, 'localhost')) {
                return $configUrl;
            }
            return 'https://vus-bemanning.se';
        }

        return $url;
    }
}
