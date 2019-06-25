<?php

namespace Avikuloff\QuickFilters;

use Avikuloff\QuickFilters\Console\Commands\MakeFilterCommand;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as DatabaseBuilder;
use Illuminate\Support\ServiceProvider;

/**
 * Class QuickFiltersServiceProvider
 * @package App\Providers
 */
class QuickFiltersServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/quickfilters.php' => config_path('quickfilters.php'),
            ], 'config');

            $this->commands([
                MakeFilterCommand::class,
            ]);
        }

        DatabaseBuilder::macro('filter', function (array $data, array $filters = null) {
            /** @var DatabaseBuilder $builder */
            $builder = $this;
            return (new QuickFilters($builder))->apply($data, $filters);
        });

        EloquentBuilder::macro('filter', function (array $data, array $filters = null) {
            /** @var EloquentBuilder $builder */
            $builder = $this;
            return (new QuickFilters($builder))->apply($data, $filters);
        });
    }
}
