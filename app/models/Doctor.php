<?php
/**
 * Created by PhpStorm.
 * User: VincentBel
 * Date: 2014/12/8
 * Time: 9:02
 */

/**
 * Class Doctor 医生模型
 */
class Doctor extends Eloquent
{

    // 当前Model对应的数据库表 —— doctor
    protected $table = 'doctor';


    // 设置表的主键
    protected $primaryKey = 'doctor_id';

}