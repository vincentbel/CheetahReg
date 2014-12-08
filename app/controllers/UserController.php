<?php
/**
 * Created by PhpStorm.
 * User: VincentBel
 * Date: 2014/12/2
 * Time: 10:49
 */

class UserController extends BaseController
{

    /**
     * 注册一个新用户
     */
    public function register()
    {
        //获取用户注册信息
        $realName = Input::get('realName');
        $mobileNumber = Input::get('mobileNumber');
        $IdCardNumber = Input::get('IdCardNumber');
        $password = Input::get('password');
        $SMSCode =  Input::get('SMSCode');

        $validator = new \Cheetah\Services\Validation\SMSValidator();

        //验证手机验证码
        if ($validator -> verifySMSCode($SMSCode)) {
            User::create($realName,$mobileNumber,$IdCardNumber,$password);
        }
    }
} 