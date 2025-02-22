<?php

namespace Hyde\Framework\Contracts;

use Illuminate\Support\Collection;

/**
 * This contract defines the static facade methods for the Route class.
 *
 * @see \Hyde\Framework\Contracts\RouteContract for the interface that each route model must implement.
 */
interface RouteFacadeContract
{
    /**
     * Get a route from the Router index for the specified route key.
     *
     * Alias for static::getFromKey().
     *
     * @param  string  $routeKey  Example: posts/foo.md
     * @return \Hyde\Framework\Contracts\RouteContract
     *
     * @throws \Hyde\Framework\Exceptions\RouteNotFoundException
     */
    public static function get(string $routeKey): RouteContract;

    /**
     * Get a route from the Router index for the specified route key.
     *
     * @param  string  $routeKey  Example: posts/foo.md
     * @return \Hyde\Framework\Contracts\RouteContract
     *
     * @throws \Hyde\Framework\Exceptions\RouteNotFoundException
     */
    public static function getFromKey(string $routeKey): RouteContract;

    /**
     * Get a route from the Router index for the specified source file path.
     *
     * @param  string  $sourceFilePath  Example: _posts/foo.md
     * @return \Hyde\Framework\Contracts\RouteContract
     *
     * @throws \Hyde\Framework\Exceptions\RouteNotFoundException
     */
    public static function getFromSource(string $sourceFilePath): RouteContract;

    /**
     * Get a route from the Router index for the supplied page model.
     *
     * @param  \Hyde\Framework\Contracts\PageContract  $page
     * @return \Hyde\Framework\Contracts\RouteContract
     *
     * @throws \Hyde\Framework\Exceptions\RouteNotFoundException
     */
    public static function getFromModel(PageContract $page): RouteContract;

    /**
     * Get all routes from the Router index.
     *
     * @return \Hyde\Framework\Foundation\RouteCollection<\Hyde\Framework\Contracts\RouteContract>
     */
    public static function all(): Collection;

    /**
     * Get the current route for the page being rendered.
     */
    public static function current(): RouteContract;

    /**
     * Get the home route, usually the index page route.
     */
    public static function home(): RouteContract;

    /**
     * Determine if the supplied route key exists in the route index.
     *
     * @param  string  $routeKey
     * @return bool
     */
    public static function exists(string $routeKey): bool;
}
