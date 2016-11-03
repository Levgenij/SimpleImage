<?php

namespace LightAdmin\Image;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class SimpleImageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        App::bind('SimpleImage', function()
        {
            return new \LightAdmin\Image\Controllers\SimpleImageController;
        });

    }
}
