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
            'real_name' => Input::get('realName'),
            'ID_card_number' => Input::get('IdCardNumber'),
            'password' => Hash::make(Input::get('password')),
            'phone_number' => Input::get('phoneNumber')
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



    public function showProfile()
    {
        $this->user->reservations;
    }
}