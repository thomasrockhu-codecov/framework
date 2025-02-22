<?php

namespace Hyde\Framework\Testing\Feature;

use Hyde\Framework\Hyde;
use Hyde\Framework\Services\RebuildService;
use Hyde\Framework\StaticPageBuilder;
use Hyde\Testing\TestCase;
use Illuminate\Support\Facades\File;

/**
 * Class BuildOutputDirectoryCanBeChangedTest.
 *
 * @todo add test for the Rebuild Service
 * @todo add test for configurable option
 */
class BuildOutputDirectoryCanBeChangedTest extends TestCase
{
    public function test_site_output_directory_can_be_changed_in_static_page_builder()
    {
        $this->file('_posts/test-post.md');

        StaticPageBuilder::$outputPath = Hyde::path('_site/build');

        (new RebuildService('_posts/test-post.md'))->execute();

        $this->assertFileExists(Hyde::path('_site/build/posts/test-post.html'));

        File::deleteDirectory(Hyde::path('_site/build'));
    }

    public function test_output_directory_is_created_if_it_does_not_exist_in_static_page_builder()
    {
        $this->file('_posts/test-post.md');
        File::deleteDirectory(Hyde::path('_site/build/foo'));
        StaticPageBuilder::$outputPath = Hyde::path('_site/build/foo');
        (new RebuildService('_posts/test-post.md'))->execute();

        $this->assertFileExists(Hyde::path('_site/build/foo/posts/test-post.html'));
        File::deleteDirectory(Hyde::path('_site/build/foo'));
    }
}
