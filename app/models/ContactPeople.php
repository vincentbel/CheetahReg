<?php
/**
 * Created by PhpStorm.
 * User: VincentBel
 * Date: 2014/12/8
 * Time: 9:05
 */

/**
 * Class ContactPeople 联系人模型
 */
class ContactPeople extends Eloquent
{

    // 当前Model对应的数据库表
    protected $table = 'contact_people';


    // 设置表的主键
    protected $primaryKey = 'contact_people_id';

    protected $fillable = array('user_id', 'real_name', 'ID_card_number', 'gender');

    // 验证出错时的错误信息
    public $error;

    /**
     * 验证数据是否合法
     * @return bool
     */
    public function isValid()
    {
        $idCardValidation = new \Cheetah\Services\Validation\IdCardAndNameValidator();

        // 验证身份证号和密码是否匹配
        if (!$idCardValidation->isIdCardAndNameMatched($this->ID_card_number, $this->real_name)) {
            $this->error = "身份证号和姓名不匹配";
            return false;
        }

        return true;
    }

    /**
     * 用户和联系人是一对多的关系
     */
    public function user()
    {
        return $this->belongsTo('User');
    }
}