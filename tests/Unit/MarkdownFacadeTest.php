<?php

namespace Hyde\Framework\Testing\Unit;

use Hyde\Framework\Models\Markdown;
use Hyde\Testing\TestCase;

/**
 * Class MarkdownConverterTest.
 *
 * @covers \Hyde\Framework\Models\Markdown
 */
class MarkdownFacadeTest extends TestCase
{
    public function test_render(): void
    {
        $markdown = '# Hello World!';

        $html = Markdown::render($markdown);

        $this->assertIsString($html);
        $this->assertEquals("<h1>Hello World!</h1>\n", $html);
    }
}
