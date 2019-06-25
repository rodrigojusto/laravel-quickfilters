<?php

namespace Avikuloff\QuickFilters\Tests\Stubs;

use Closure;
use Illuminate\Support\Collection;

class NameFilterStub
{

    /**
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query
     * @param Closure $next
     * @param Collection $collection
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function handle($query, Closure $next, Collection $collection)
    {
        if ($collection->has('name')) {
            $query->where('table_name', $collection->get('name'));
        }

        return $next($query);
    }
}
