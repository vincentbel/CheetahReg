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
    public function addUser()
    {

    }

}
