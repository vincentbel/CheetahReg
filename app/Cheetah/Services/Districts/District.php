<?php

namespace Cheetah\Services\District;

use Illuminate\Support\Facades\Facade;

class District extends Facade {

    protected static function getFacadeAccessor()
    {
        return 'district';
    }

}