<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ((bool) env('APP_DEBUG', false)) {
            $dbLogger = Log::build([
                'driver' => 'single',
                'path' => storage_path('logs/database.log'),
            ]);;

            DB::connection('app')
                ->listen(function ($query) use ($dbLogger) {
                    $dbLogger->info(
                        $query->sql,
                        $query->bindings,
                        $query->time
                    );
                });
        }
    }
}
