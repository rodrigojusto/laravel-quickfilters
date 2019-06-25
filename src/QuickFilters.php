<?php

namespace Avikuloff\QuickFilters;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Mpociot\Pipeline\Pipeline;

class QuickFilters
{
    /**
     * List filters
     *
     * @var array<int, string> The array values ​​must contain the fully qualified name of the Filter class.
     */
    public $filters;
    /**
     * Eloquent or Database query builder instance
     *
     * @var EloquentBuilder|\Illuminate\Database\Query\Builder
     */
    protected $builder;
    /**
     * Query builder instance
     *
     * @var \Illuminate\Database\Query\Builder
     */
    protected $query;

    /**
     * QuickFilter constructor.
     *
     * @param \Illuminate\Database\Query\Builder|EloquentBuilder $builder
     */
    public function __construct($builder)
    {
        $this->builder = $builder;

        $this->query = $builder instanceof EloquentBuilder
            ? $builder->getQuery()
            : $builder;

        $this->filters = config('quickfilters.groups.' . $this->query->from);
    }

    /**
     * Applies all filters except those listed.
     *
     * @param array $data
     * @param array<int, string> $filters
     * @return \Illuminate\Database\Query\Builder|EloquentBuilder
     */
    public function except(array $data, array $filters)
    {
        $this->filters = array_diff($this->filters, $filters);

        return $this->apply($data);
    }

    /**
     * Applies filters. If a filter list is transmitted,
     * then only those filters that are listed in the list are applied.
     *
     * @param array $data
     * @param array|null $filters
     * @return \Illuminate\Database\Query\Builder|EloquentBuilder
     */
    public function apply(array $data, array $filters = null)
    {
        if (!is_null($filters)) {
            $this->filters = array_intersect($this->filters, $filters);
        }

        return (new Pipeline())
            ->send($this->builder)
            ->with(collect($data))
            ->through($this->filters)
            ->then(function ($builder) {
                return $builder;
            });
    }

    /**
     * Adds new filters to existing ones.
     *
     * @param array<int, string> $filters
     * @return void
     */
    public function addFilters(array $filters): void
    {
        $this->filters = array_merge($this->filters, $filters);
    }

    /**
     * Filters getter
     *
     * @return array<int, string>
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * Filters setter
     *
     * @param array<int, string> $filters
     * @return void
     */
    public function setFilters(array $filters): void
    {
        $this->filters = $filters;
    }
}