<?php

namespace Avikuloff\QuickFilter\Contracts;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

/**
 * Interface Filter
 * @package Avikuloff\QuickFilter\Filters
 */
interface Filter
{
    /**
     * @param Builder $query
     * @param Closure $next
     * @param Collection $collection
     * @return Builder
     */
    public function handle(Builder $query, Closure $next, Collection $collection): Builder;
}
