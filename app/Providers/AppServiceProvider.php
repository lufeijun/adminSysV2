<?php

namespace App\Providers;

use App\System\Menu;
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
        \DB::listen(function ($query) {
            // \Log::info($query->sql.'  >>>>>>>>>>  '.implode(' | ', $query->bindings));
            \Log::info(
                $query->sql.',time=' . $query->time, // 查询单位，时间是 毫秒
                $query->bindings
            );
        });

        \View::share('pagename', Menu::getHtmlTitle());
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
