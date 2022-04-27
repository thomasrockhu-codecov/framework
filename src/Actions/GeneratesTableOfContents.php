<?php

namespace Hyde\Framework\Actions;

use Hyde\Framework\Contracts\ActionContract;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\Extension\TableOfContents\TableOfContentsExtension;
use League\CommonMark\MarkdownConverter;

/**
 * Generates a table of contents for the Markdown document.
 *
 * @see \Tests\Feature\Actions\GeneratesTableOfContentsTest
 */
class GeneratesTableOfContents implements ActionContract
{
    protected string $markdown;

    public function __construct(string $markdown)
    {
        $this->markdown = $markdown;
    }

    public function execute(): string
    {
        $config = [
            'table_of_contents' => [
                'html_class' => 'table-of-contents',
                'position' => 'top',
                'style' => 'bullet',
                'min_heading_level' => config('hyde.documentationPageTableOfContents.minHeadingLevel', 2),
                'max_heading_level' => config('hyde.documentationPageTableOfContents.maxHeadingLevel', 4),
                'normalize' => 'relative',
            ],
            'heading_permalink' => [
                'fragment_prefix' => '',
            ],
        ];

        $environment = new Environment($config);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new HeadingPermalinkExtension());
        $environment->addExtension(new TableOfContentsExtension());

        $converter = new MarkdownConverter($environment);
        $html = $converter->convert("[[END_TOC]]\n".$this->markdown)->getContent();

        // Return everything before the [[END_TOC]] marker.
        return substr($html, 0, strpos($html, '<p>[[END_TOC]]'));
    }
}
