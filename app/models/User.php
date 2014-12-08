<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

    use UserTrait, RemindableTrait;

    // 当前Model对应的数据库表 —— user
    protected $table = 'user';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password', 'remember_token');

    // 设置user表的主键
    protected $primaryKey = 'user_id';


    /**
     * 在数据库表中新建一个用户
     *
     * 参数不完整，请自行补充
     */
    public static function addUser($realName,$mobileNumber,$IdCardNumber,$password)
    {
       //对各个字段的验证要求
        $validator = Validator::make(
            array(
                    'realName'=> $realName,
                    'mobile_number' => $mobileNumber,
                    'ID_card_number'=> $IdCardNumber,
                    'password' => $password
            ),
            array(
                    'realName' => 'required',
                    'mobile_number' => 'required|size:11|numeric|unique:user',
                    'ID_card_number' => 'required|unique:user',
                    'password' => 'required|between:6,20'
            )
        );

        $IDCardValidator = new \Cheetah\Services\Validation\IdCardAndNameValidator();

        if ($IDCardValidator -> isIdCardAndNameMatched($realName,$IdCardNumber) && $validator -> passes()) {
            //在数据库中创建一个用户
            parent::create(
                array(
                    'real_name' => $realName,
                    'ID_card_number' => $IdCardNumber,
                    'password' => $password,
                    'mobile_number' => $mobileNumber
                )
            );
        }
    }

}
