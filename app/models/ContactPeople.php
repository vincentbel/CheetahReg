<?php
/**
 * Created by PhpStorm.
 * User: VincentBel
 * Date: 2014/12/8
 * Time: 9:05
 */
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
/**
 * Class ContactPeople 联系人模型
 */
class ContactPeople extends Eloquent implements UserInterface, RemindableInterface
{
    use UserTrait, RemindableTrait;

    // 当前Model对应的数据库表 --contact_people
    protected $table = 'contact_people';


    // 设置表的主键
    protected $primaryKey = 'contact_people_id';



    public function isValid()
    {
        $idCardValidation = new \Cheetah\Services\Validation\IdCardAndNameValidator();

        // 验证身份证号和密码是否匹配
        if ( ! $idCardValidation->isIdCardAndNameMatched($this->ID_card_number, $this->real_name)) {
            $this->error = "身份证号和姓名不匹配";
            return false;
        }

        return true;
    }

    public function user()
    {
        return $this->belongsTo('User');
    }
}