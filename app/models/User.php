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

    protected $fillable = array('real_name', 'ID_card_number', 'password', 'mobile_number', 'gender');

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
     * 用户和联系人是一对多的关系
     */
    public function contactPeople()
    {
        return $this->hasMany('ContactPeople');
    }

    /**
     * user 表和 reservation_number_info 表通过 contact_people 表是 间接多对多 的关系
     *
     * @param null $startDate
     * @param null $endDate
     * @return static Collection
     */
    public function reservationNumbers($startDate = null, $endDate = null)
    {
        // 设置默认开始时间为今天
        $startDate = ($startDate === null) ? date('Y-m-d', time()) : $startDate;

        // 设置默认结束时间为 一年后的今天
        $endDate = ($endDate === null) ? date('Y-m-d', strtotime('+1 year')) : $endDate;

        $reservations = new \Illuminate\Database\Eloquent\Collection();
        $contactPeoples = $this->contactPeople;
        foreach ($contactPeoples as $key => $contactPeople) {

            // 判断此联系人的预约次数是否为0
            if ( ! $contactPeople->reservationNumbers->isEmpty() ) {
                foreach ($contactPeople->reservationNumbers as $reservationNumber) {

                    // 只返回介于 $startDate 和 $endDate 之间的预约
                    if ($reservationNumber->date >= $startDate && $reservationNumber->date <= $endDate) {

                        $department = Department::find($reservationNumber->department_id);

                        $reservation = array();
                        $reservation['reservationNumberInfoId'] = $reservationNumber->reservation_number_info_id;
                        $reservation['date'] = $reservationNumber->date;
                        $reservation['hospital'] = $department->hospital->hospital_name;
                        $reservation['departmentId'] = $reservationNumber->department_id;
                        $reservation['departmentName'] = $department->pluck('department_name');
                        $reservation['contactPeopleId'] = $contactPeople->contact_people_id;
                        $reservation['contactPeopleName'] = $contactPeople->real_name;
                        $reservation['timeInterval'] = $reservationNumber->start_time.' - '.$reservationNumber->end_time;
                        $reservation['reservationStatus'] = $reservationNumber->pivot->reservation_status;
                        $reservation['attendance'] = $reservationNumber->pivot->attendance;
                        $reservations->add($reservation);
                    }
                }
            }
        }

        return $reservations;
    }

    /**
     * 取得联系人表中的自己
     *
     * @return mixed
     */
    public function MySelfInContactPeople()
    {
        $myself = $this->contactPeople->filter(function($contactPeople)
        {
            if ($contactPeople->is_myself == 1) {
                return true;
            }
        });
        return $myself->first();
    }
}
