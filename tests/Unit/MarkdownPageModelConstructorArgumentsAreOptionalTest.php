<?php

namespace Hyde\Framework\Testing\Unit;

use Hyde\Framework\Models\DocumentationPage;
use Hyde\Framework\Models\MarkdownPage;
use Hyde\Framework\Models\MarkdownPost;
use Hyde\Testing\TestCase;

class MarkdownPageModelConstructorArgumentsAreOptionalTest extends TestCase
{
    public function test_markdown_page_model_constructor_arguments_are_optional()
    {
        $models = [
            MarkdownPage::class,
            MarkdownPost::class,
            DocumentationPage::class,
        ];

        foreach ($models as $model) {
            $this->assertInstanceOf($model, new $model());
        }
    }
}
