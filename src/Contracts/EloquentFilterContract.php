<?php

namespace Avikuloff\QuickFilters\Contracts;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Interface EloquentFilter
 * @package Avikuloff\QuickFilters\Filters
 */
interface EloquentFilterContract
{
    /**
     * @param Builder $builder
     * @param Closure $next
     * @param Collection $collection
     * @return Builder
     */
    public function handle(Builder $builder, Closure $next, Collection $collection): Builder;
}
