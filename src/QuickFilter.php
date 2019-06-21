<?php

namespace Avikuloff\QuickFilter;

use Illuminate\Database\Eloquent\Builder;
use Mpociot\Pipeline\Pipeline;

/**
 * Class QuickFilter
 * @package Avikuloff\QuickFilter
 */
class QuickFilter
{
    /**
     * Carry through the pipeline Query Builder, applying filters.
     *
     * @param Builder $builder
     * @param array $data
     * @param array|null $filters
     * @return Builder
     */
    public function apply(Builder $builder, array $data, array $filters = null): Builder
    {
        $filters = $this->getFilters($builder, $filters);

        return (new Pipeline())
            ->send($builder)
            ->with(collect($data))
            ->through($filters)
            ->then(function ($builder) {
                return $builder;
            });
    }

    /**
     * If the list of keys is passed, it returns the list of filters otherwise,
     * it will return all available filters for the model.
     *
     * @param Builder $builder
     * @param array|null $filters
     * @return array
     */
    protected function getFilters(Builder $builder, array $filters = null): array
    {
        $availableFilters = $this->getAvailableFilters(get_class($builder->getModel()));

        if (is_null($filters)) {
            return $availableFilters;
        }

        return array_intersect_key($availableFilters, array_flip($filters));
    }

    /**
     * Returns a list of all filters available for the model.
     *
     * @param string $model
     * @return array
     */
    protected function getAvailableFilters(string $model): array
    {
        return config('quickfilter.groups.' . $model);
    }
}
