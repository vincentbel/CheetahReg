<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface
{

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

    protected $fillable = array('real_name', 'ID_card_number', 'password', 'phone_number');

    // 验证规则
    public static $rules = [
        'real_name' => 'required',
        'ID_card_number' => 'required|unique:user',
        'password' => 'required|min:6',
        'mobile_number' => 'phone|unique:user'
    ];

    // 验证出错时的错误信息
    public $error;


    public function isValid()
    {
        $idCardValidation = new \Cheetah\Services\Validation\IdCardAndNameValidator();

        // 验证身份证号和密码是否匹配
        if ( ! $idCardValidation->isIdCardAndNameMatched($this->ID_card_number, $this->real_name)) {
            $this->error = "身份证号和姓名不匹配";
            return false;
        }

        // 根据定下的rules验证个字段
        $validation = Validator::make($this->attributes, $this::$rules);

        if ($validation->fails()) {
            $this->error = $validation->messages();
            return false;
        }

        return true;
    }


    /**
     * user 表和 reservation 表是一对多 的关系
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reservations()
    {
        return $this->hasMany('Reservation');
    }

}
