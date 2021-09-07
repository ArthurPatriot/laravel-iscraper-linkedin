<?php

namespace Iscraper;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Arthurpatriot\LaravelIscraperLinkedin\Skeleton\SkeletonClass
 */
class IscraperFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-iscraper-linkedin';
    }
}
