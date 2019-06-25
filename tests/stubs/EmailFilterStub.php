<?php

namespace Avikuloff\QuickFilters\Tests\Stubs;

use Closure;
use Illuminate\Support\Collection;

class EmailFilterStub
{

    /**
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query
     * @param Closure $next
     * @param Collection $collection
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function handle($query, Closure $next, Collection $collection)
    {
        if ($collection->has('email')) {
            $query->where('email', $collection->get('email'));
        }

        return $next($query);
    }
}
