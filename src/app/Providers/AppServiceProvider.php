<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use App\Models\Category;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        if (Schema::hasTable('categories')) {
            // ここでのみCategory::all()などを実行
            view()->share('categories', \App\Models\Category::all());
        }
    }
}
