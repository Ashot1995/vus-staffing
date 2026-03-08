<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;
    public function test_sitemap_uses_request_domain_not_localhost(): void
    {
        $response = $this->withServerVariables([
            'HTTP_HOST' => 'vus-bemanning.se',
            'HTTPS' => 'on',
        ])->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');

        $content = $response->getContent();
        $this->assertStringContainsString('vus-bemanning.se', $content);
        $this->assertStringNotContainsString('localhost', $content);
    }

    public function test_sitemap_contains_required_static_pages(): void
    {
        $response = $this->withServerVariables([
            'HTTP_HOST' => 'vus-bemanning.se',
            'HTTPS' => 'on',
        ])->get('/sitemap.xml');

        $content = $response->getContent();
        $baseUrl = 'https://vus-bemanning.se';
        $this->assertStringContainsString("<loc>{$baseUrl}</loc>", $content);
        $this->assertStringContainsString("{$baseUrl}/om-oss", $content);
        $this->assertStringContainsString("{$baseUrl}/jobb", $content);
        $this->assertStringContainsString("{$baseUrl}/blog", $content);
        $this->assertStringContainsString("{$baseUrl}/foretagsvarden", $content);
        $this->assertStringContainsString("{$baseUrl}/kandidatinformation", $content);
    }

    public function test_sitemap_is_valid_xml(): void
    {
        $response = $this->withServerVariables([
            'HTTP_HOST' => 'vus-bemanning.se',
            'HTTPS' => 'on',
        ])->get('/sitemap.xml');

        $content = $response->getContent();
        $xml = @simplexml_load_string($content);
        $this->assertNotFalse($xml, 'Sitemap should be valid XML');
    }

    public function test_robots_txt_uses_correct_sitemap_url(): void
    {
        $response = $this->withServerVariables([
            'HTTP_HOST' => 'vus-bemanning.se',
            'HTTPS' => 'on',
        ])->get('/robots.txt');

        $response->assertStatus(200);
        $this->assertStringContainsString('text/plain', $response->headers->get('Content-Type'));

        $content = $response->getContent();
        $this->assertStringContainsString('Sitemap: https://vus-bemanning.se/sitemap.xml', $content);
        $this->assertStringNotContainsString('localhost', $content);
    }
}
