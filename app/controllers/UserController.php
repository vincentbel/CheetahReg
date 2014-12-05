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
        $realName = Input::get('real_name');
        $mobileNumber = Input::get('mobile_number');
        $idCardNumber = Input::get('ID_card_number');
        $password =Input::get('password');

        //验证手机号格式
        function isMobileNumber($Argv)
        {
            $RegExp = "/^13[0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|189[0-9]{8}$/";
            return preg_match($RegExp, $Argv) ? true : false;
        }

        //验证手机号是否被注册
        function isRegistered($Argv)
        {
            $sql = "SELECT * FROM user WHERE mobile_number = '$Argv'";
            $res = mysql_query($sql);
            $rows=mysql_num_rows($res);
            return $rows;
        }

        //验证身份证号格式
        function isIdCardNumber($Argv)
        {
            $RegExp = "/^[1-9]{5}[1-9]{3}((0)|(1[0-2]))(([0|1|2])|3[0-1]){4}$/";
            return preg_match($RegExp, $Argv) ? true : false;
        }
    }
} 