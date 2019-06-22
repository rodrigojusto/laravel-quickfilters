<?php

namespace Avikuloff\QuickFilters;

use Avikuloff\QuickFilters\Console\Commands\MakeFilterCommand;
use Illuminate\Database\Eloquent\Builder;
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

        Builder::macro('filter', function (array $data, array $filters = null) {
            /** @var Builder $builder */
            $builder = $this;
            return (new EloquentFilter())->apply($builder, $data, $filters);
        });
    }
}
