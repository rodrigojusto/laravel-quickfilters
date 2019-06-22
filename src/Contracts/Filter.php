<?php

namespace Avikuloff\QuickFilters\Contracts;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Interface Filter
 * @package Avikuloff\QuickFilters\Filters
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
