<?php

namespace Cheetah\Services\District;

use Illuminate\Support\ServiceProvider;

class DistrictServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->singleton('district', function()
        {
            return new District;
        });
    }
}
