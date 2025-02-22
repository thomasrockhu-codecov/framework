<?php

namespace Hyde\Framework\Testing\Feature\Services;

use Hyde\Framework\Hyde;
use Hyde\Framework\Services\SitemapService;
use Hyde\Testing\TestCase;
use Illuminate\Support\Facades\File;

/**
 * @covers \Hyde\Framework\Services\SitemapService
 */
class SitemapServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        File::deleteDirectory(Hyde::path('_pages'));
        File::makeDirectory(Hyde::path('_pages'));
        copy(Hyde::vendorPath('resources/views/homepages/welcome.blade.php'), Hyde::path('_pages/index.blade.php'));
        copy(Hyde::vendorPath('resources/views/pages/404.blade.php'), Hyde::path('_pages/404.blade.php'));
    }

    public function test_service_instantiates_xml_element()
    {
        $service = new SitemapService();
        $this->assertInstanceOf('SimpleXMLElement', $service->xmlElement);
    }

    public function test_constructor_throws_exception_when_missing_simplexml_extension()
    {
        config(['testing.mock_disabled_extensions' => true]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('The ext-simplexml extension is not installed, but is required to generate RSS feeds.');
        new SitemapService();
    }

    public function test_generate_adds_default_pages_to_xml()
    {
        $service = new SitemapService();
        $service->generate();

        // The test runner has an index and 404 page, so we are using that as a baseline
        $this->assertCount(2, $service->xmlElement->url);
    }

    public function test_generate_adds_markdown_pages_to_xml()
    {
        Hyde::touch(('_pages/foo.md'));

        $service = new SitemapService();
        $service->generate();

        $this->assertCount(3, $service->xmlElement->url);

        unlink(Hyde::path('_pages/foo.md'));
    }

    public function test_generate_adds_markdown_posts_to_xml()
    {
        Hyde::touch(('_posts/foo.md'));

        $service = new SitemapService();
        $service->generate();

        $this->assertCount(3, $service->xmlElement->url);

        unlink(Hyde::path('_posts/foo.md'));
    }

    public function test_generate_adds_documentation_pages_to_xml()
    {
        Hyde::touch(('_docs/foo.md'));

        $service = new SitemapService();
        $service->generate();

        $this->assertCount(3, $service->xmlElement->url);

        unlink(Hyde::path('_docs/foo.md'));
    }

    public function test_get_xml_returns_xml_string()
    {
        $service = new SitemapService();
        $service->generate();
        $xml = $service->getXML();

        $this->assertIsString($xml);
        $this->assertStringStartsWith('<?xml version="1.0" encoding="UTF-8"?>', $xml);
    }

    public function test_generate_sitemap_shorthand_method_returns_xml_string()
    {
        $xml = SitemapService::generateSitemap();

        $this->assertIsString($xml);
        $this->assertStringStartsWith('<?xml version="1.0" encoding="UTF-8"?>', $xml);
    }

    public function test_url_item_is_generated_correctly()
    {
        config(['site.pretty_urls' => false]);
        config(['site.url' => 'https://example.com']);
        Hyde::touch(('_pages/0-test.blade.php'));

        $service = new SitemapService();
        $service->generate();

        $url = $service->xmlElement->url[0];
        $this->assertEquals('https://example.com/0-test.html', $url->loc);
        $this->assertEquals('daily', $url->changefreq);
        $this->assertTrue(isset($url->lastmod));

        unlink(Hyde::path('_pages/0-test.blade.php'));
    }

    public function test_url_item_is_generated_with_pretty_urls_if_enabled()
    {
        config(['site.pretty_urls' => true]);
        config(['site.url' => 'https://example.com']);
        Hyde::touch(('_pages/0-test.blade.php'));

        $service = new SitemapService();
        $service->generate();

        $url = $service->xmlElement->url[0];
        $this->assertEquals('https://example.com/0-test', $url->loc);

        unlink(Hyde::path('_pages/0-test.blade.php'));
    }

    public function test_all_route_types_are_discovered()
    {
        config(['site.url' => 'foo']);
        Hyde::unlink(['_pages/index.blade.php', '_pages/404.blade.php']);

        $files = [
            '_pages/blade.blade.php',
            '_pages/markdown.md',
            '_posts/post.md',
            '_docs/doc.md',
        ];

        Hyde::touch($files);

        $service = new SitemapService();
        $service->generate();

        $this->assertCount(4, $service->xmlElement->url);

        $this->assertEquals('foo/blade.html', $service->xmlElement->url[0]->loc);
        $this->assertEquals('foo/markdown.html', $service->xmlElement->url[1]->loc);
        $this->assertEquals('foo/posts/post.html', $service->xmlElement->url[2]->loc);
        $this->assertEquals('foo/docs/doc.html', $service->xmlElement->url[3]->loc);

        Hyde::unlink($files);

        copy(Hyde::vendorPath('resources/views/homepages/welcome.blade.php'), Hyde::path('_pages/index.blade.php'));
        copy(Hyde::vendorPath('resources/views/pages/404.blade.php'), Hyde::path('_pages/404.blade.php'));
    }
}
