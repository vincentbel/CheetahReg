<?php
/**
 * Author: VincentBel
 * Date: 2014/12/14
 * Time: 12:35
 */

namespace Cheetah\Services\Validation;


use Illuminate\Validation\Validator;

class PhoneValidationRule extends Validator {

    public function validatePhone($attribute, $value, $parameters)
    {
        return preg_match("/^([0-9\s\-\+\(\)]*)$/", $value);
    }

}