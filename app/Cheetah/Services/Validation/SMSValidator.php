<?php
/**
 * Created by PhpStorm.
 * User: VincentBel
 * Date: 2014/12/1
 * Time: 20:35
 */

namespace Cheetah\Services\Validation;

/**
 * Class SMSValidator
 * 发送短信验证码和验证短信验证码类
 *
 * @package Cheetah\Services\Validation
 */
class SMSValidator
{
    /**
     * @param $phoneNumber
     */
    public function sendSMS($phoneNumber)
    {
        return "function sendSMS() retched.";
    }

    /**
     *
     */
    public function verifySMSCode()
    {
        return "function verifySMSCode() retched.";
    }
}