<?php

namespace Avikuloff\QuickFilter;

use Mpociot\Pipeline\Pipeline;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class QuickFilter
 * @package Avikuloff\QuickFilter
 */
class QuickFilter
{
    /**
     * @param Builder $builder
     * @param array $data
     * @param array|null $filters
     * @return Builder
     */
    public function apply(Builder $builder, array $data, array $filters = null): Builder
    {
        $filters = $this->getFilters($builder->getModel(), $filters);

        return (new Pipeline())
            ->send($builder)
            ->with(collect($data))
            ->through($filters)
            ->then(function ($builder) {
                return $builder;
            });
    }

    /**
     * @param Model $model
     * @param array|null $filters
     * @return array
     */
    protected function getFilters(Model $model, array $filters = null): array
    {
        // TODO Change filter list provider
        if (is_null($filters)) {
            return $model->filters;
        }

        return array_intersect_key($model->filters, array_flip($filters));
    }
}
