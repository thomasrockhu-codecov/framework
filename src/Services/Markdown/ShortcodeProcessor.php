<?php

namespace Hyde\Framework\Services\Markdown;

use Hyde\Framework\Contracts\MarkdownProcessorContract;
use Hyde\Framework\Contracts\MarkdownShortcodeContract;
use Hyde\Framework\Services\Markdown\Shortcodes\AbstractColoredBlockquote;

/**
 * Handle shortcode processing for Markdown conversions.
 *
 * The shortcode system has a few limitations, as it is meant to be simple
 * by design so that it is easy to understand how the code works, and
 * what each shortcode does. Shortcodes are expanded on a per-line basis,
 * and do not support multi-line input. Shortcodes are expected to be
 * the very first thing on a line. The signature is a static string
 * that is used to identify the shortcode. The built-in shortcodes
 * do not use regex, as that would make them harder to read.
 *
 * @todo Refactor shortcode manager to singleton as it does not need to be re-instantiated
 *      for each Markdown conversion.
 *
 * @see \Tests\Feature\Services\Markdown\ShortcodeProcessorTest
 */
class ShortcodeProcessor implements MarkdownProcessorContract
{
    /**
     * The input Markdown document body.
     */
    protected string $input;

    /**
     * The processed Markdown document body.
     */
    protected string $output;

    /**
     * The activated shortcode instances.
     */
    public array $shortcodes;

    public function __construct(string $input)
    {
        $this->input = $input;

        $this->discoverShortcodes();
    }

    public function processInput(): self
    {
        $this->output = implode("\n", array_map(function ($line) {
            return $this->expandShortcode($line);
        }, explode("\n", $this->input)));

        return $this;
    }

    public function getOutput(): string
    {
        return $this->output;
    }

    public function run(): string
    {
        return $this->processInput()->getOutput();
    }

    public static function process(string $input): string
    {
        return (new static($input))->run();
    }

    protected function discoverShortcodes(): void
    {
        // Discover default shortcodes @todo make this configurable
        foreach (glob(__DIR__.'/shortcodes/*.php') as $file) {
            $class = 'Hyde\Framework\Services\Markdown\Shortcodes\\'. str_replace('.php', '', basename($file));

            if (class_exists($class)
                && is_subclass_of($class, MarkdownShortcodeContract::class)
                && ! str_starts_with(basename($file), 'Abstract')) {
                $this->addShortcode(new $class());
            }
        }

        // Register any provided shortcodes
        $this->addShortcodesFromArray(array_merge(
            AbstractColoredBlockquote::get(),
        ));
    }

    public function addShortcodesFromArray(array $shortcodes): self
    {
        foreach ($shortcodes as $shortcode) {
            $this->addShortcode($shortcode);
        }

        return $this;
    }

    public function addShortcode(MarkdownShortcodeContract $shortcode): self
    {
        $this->shortcodes[$shortcode::signature()] = $shortcode;

        return $this;
    }

    protected function expandShortcode(string $line): string
    {
        return array_key_exists($signature = $this->discoverSignature($line), $this->shortcodes)
            ? $this->shortcodes[$signature]::resolve($line)
            : $line;
    }

    protected function discoverSignature(string $line): string
    {
        return str_contains($line, ' ') ? substr($line, 0, strpos($line, ' ')) : $line;
    }
}