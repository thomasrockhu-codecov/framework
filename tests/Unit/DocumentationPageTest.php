<?php

namespace Hyde\Framework\Testing\Unit;

use Hyde\Framework\HydeServiceProvider;
use Hyde\Framework\Models\Pages\DocumentationPage;
use Hyde\Testing\TestCase;

/**
 * @covers \Hyde\Framework\Models\Pages\DocumentationPage
 */
class DocumentationPageTest extends TestCase
{
    public function test_can_generate_table_of_contents()
    {
        $page = (new DocumentationPage([], '# Foo'));
        $this->assertIsString($page->tableOfContents);
    }

    public function test_can_get_current_page_path()
    {
        $page = (new DocumentationPage([], '', '', 'foo'));
        $this->assertEquals('docs/foo', $page->getCurrentPagePath());

        config(['docs.output_directory' => 'documentation/latest/']);
        (new HydeServiceProvider($this->app))->register();
        $this->assertEquals('documentation/latest/foo', $page->getCurrentPagePath());
    }

    public function test_can_get_online_source_path()
    {
        $page = (new DocumentationPage([], ''));
        $this->assertFalse($page->getOnlineSourcePath());
    }

    public function test_can_get_online_source_path_with_source_file_location_base()
    {
        config(['docs.source_file_location_base' => 'docs.example.com/edit']);
        $page = (new DocumentationPage([], '', '', 'foo'));
        $this->assertEquals('docs.example.com/edit/foo.md', $page->getOnlineSourcePath());
    }

    public function test_can_get_online_source_path_with_trailing_slash()
    {
        $page = (new DocumentationPage([], '', '', 'foo'));

        config(['docs.source_file_location_base' => 'edit/']);
        $this->assertEquals('edit/foo.md', $page->getOnlineSourcePath());

        config(['docs.source_file_location_base' => 'edit']);
        $this->assertEquals('edit/foo.md', $page->getOnlineSourcePath());
    }

    public function test_can_get_documentation_output_path()
    {
        $this->assertEquals('docs', DocumentationPage::getDocumentationOutputPath());
    }

    public function test_can_get_documentation_output_path_with_custom_output_directory()
    {
        config(['docs.output_directory' => 'foo']);
        (new HydeServiceProvider($this->app))->register();
        $this->assertEquals('foo', DocumentationPage::getDocumentationOutputPath());
    }

    public function test_can_get_documentation_output_path_with_trailing_slashes()
    {
        $tests = [
            'foo',
            'foo/',
            'foo//',
            'foo\\',
            '/foo/',
        ];

        foreach ($tests as $test) {
            config(['docs.output_directory' => $test]);
            (new HydeServiceProvider($this->app))->register();
            $this->assertEquals('foo', DocumentationPage::getDocumentationOutputPath());
        }
    }
}
