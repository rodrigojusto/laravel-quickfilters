<?php

namespace Avikuloff\QuickFilter\Tests;

use Avikuloff\QuickFilter\Contracts\Filter;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class NameFilterStub implements Filter
{
    public function handle(Builder $query, Closure $next, Collection $collection): Builder
    {
        if ($collection->has('name')) {
            $query->where('table_name', $collection->get('name'));
        }

        return $next($query);
    }
}