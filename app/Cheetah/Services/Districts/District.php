<?php

namespace Cheetah\Services\Districts;

use Illuminate\Support\Facades\Facade;

class District extends Facade {

    protected static function getFacadeAccessor()
    {
        return 'district';
    }

}