<?php

namespace Avikuloff\QuickFilter;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Avikuloff\QuickFilter\Console\Commands\MakeFilterCommand;

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
            $this->bootCommands();
        }

        $this->bootMacros();
    }

    /**
     * Boot the custom commands
     *
     * @return void
     */
    private function bootCommands()
    {
        $this->commands([
            MakeFilterCommand::class,
        ]);
    }

    /**
     * Boot macros
     */
    private function bootMacros()
    {
        Builder::macro('filter', function (array $data, array $filters = null) {
            return (new quickFilter())->apply($this, $data, $filters);
        });
    }
}
