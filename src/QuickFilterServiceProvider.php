<?php

namespace Avikuloff\QuickFilter;

use Avikuloff\QuickFilter\Console\Commands\MakeFilterCommand;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

/**
 * Class QuickFiltersServiceProvider
 * @package App\Providers
 */
class QuickFilterServiceProvider extends ServiceProvider
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
                __DIR__ . '/../config/quickfilter.php' => config_path('quickfilter.php'),
            ], 'config');

            $this->commands([
                MakeFilterCommand::class,
            ]);
        }

        Builder::macro('filter', function (array $data, array $filters = null) {
            /** @var Builder $builder */
            $builder = $this;
            return (new QuickFilter())->apply($builder, $data, $filters);
        });
    }
}
