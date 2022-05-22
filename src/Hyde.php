<?php

namespace Hyde\Framework;

use Composer\InstalledVersions;
use Hyde\Framework\Concerns\Internal\AssetManager;
use Hyde\Framework\Concerns\Internal\FileHelpers;
use Hyde\Framework\Concerns\Internal\FluentPathHelpers;
use Hyde\Framework\Helpers\Features;
use Hyde\Framework\Models\Parsers\MarkdownPostParser;
use Hyde\Framework\Services\CollectionService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * General facade for Hyde services.
 *
 * @author  Caen De Silva <caen@desilva.se>
 * @copyright 2022 Caen De Silva
 * @license MIT License
 *
 * @link https://hydephp.com/
 */
class Hyde
{
    use FileHelpers;
    use AssetManager;
    use FluentPathHelpers;

    protected static string $basePath;

    public static function version(): string
    {
        return InstalledVersions::getPrettyVersion('hyde/framework') ?: 'unreleased';
    }

    public static function getBasePath(): string
    {
        if (! isset(static::$basePath)) {
            static::$basePath = getcwd();
        }

        return static::$basePath;
    }

    public static function setBasePath(string $path): void
    {
        static::$basePath = $path;
    }

    public static function titleFromSlug(string $slug): string
    {
        return Str::title(str_replace('-', ' ', ($slug)));
    }

    /**
     * @throws \Exception
     */
    public static function getLatestPosts(): Collection
    {
        $collection = new Collection();

        foreach (CollectionService::getMarkdownPostList() as $filepath) {
            $collection->push((new MarkdownPostParser(basename($filepath, '.md')))->get());
        }

        return $collection->sortByDesc('matter.date');
    }

    public static function features(string $feature): bool
    {
        return Features::enabled($feature);
    }
}
