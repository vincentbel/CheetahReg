<?php
/**
 * Created by PhpStorm.
 * User: VincentBel
 * Date: 2014/12/2
 * Time: 10:49
 */

class UserController extends BaseController
{

    protected $user;

    function __construct(User $user)
    {
        $this->user = $user;
    }


    /**
     * 注册一个新用户
     */
    public function register()
    {

        $SMSCode = Input::get('SMSCode');


        $validator = New \Cheetah\Services\Validation\SMSValidator();

        // 验证手机验证码是否正确
        if (empty($SMSCode) || ! $validator -> verifySMSCode($SMSCode)) {
            return Response::json(array(
                'success' => 0,
                'message' => '手机验证码错误',
            ));
        }

        $input = array(
            'gender' => Input::get('gender'),
            'real_name' => Input::get('realName'),
            'ID_card_number' => Input::get('IdCardNumber'),
            'password' => Hash::make(Input::get('password')),
            'mobile_number' => Input::get('phoneNumber')
        );

        $this->user->fill($input);

        // 如果验证不通过，返回错误信息
        if ( ! $this->user->isValid()) {
            return Response::json(array(
                'success' => 0,
                'message' => $this->user->error,
            ));
        }

        // 验证成功，保存用户到数据库中
        $this->user->save();

        return Response::json(array(
            'success' => 1,
            'message' => "注册成功",
        ));

    }


    /**
     * 登录
     */
    public function login()
    {
        // 登录的身份证号或者手机号
        $mixPassport = Input::get('mixPassport');

        // 登录密码
        $password = Input::get('password');


        // 返回的信息
        $response = array();

        if (strlen($mixPassport) == '11') {
            // 使用手机号登录

            if (Auth::attempt(array('mobile_number' => $mixPassport, 'password' => Hash::make($password)), true)) {
                $response['success'] = 1;
                $response['message'] = '成功通过手机号登录';
            } else {
                // 通过手机号登录失败
                $response['success'] = 0;
                $response['message'] = '手机号或者密码错误';
            }

        } elseif (\Cheetah\Services\Validation\IdCardAndNameValidator::isIdCardCorrect($mixPassport)) {
            // 使用身份证号登录

            if (Auth::attempt(array('ID_Card_Number' => $mixPassport, 'password' => Hash::make($password)), true)) {
                $response['success'] = 1;
                $response['message'] = '成功通过身份证号登录';
            } else {
                $response['success'] = 0;
                $response['message'] = '身份证号或者密码错误';
            }
        } else {
            // 输入不符合规范

            $response['success'] = 0;
            $response['message'] = '输入有误';
        }

        return Response::json($response);
    }

    public function showProfile()
    {
        $this->user->reservations;

    }
}