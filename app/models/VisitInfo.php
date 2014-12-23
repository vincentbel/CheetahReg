<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2014/12/23
 * Time: 22:39
 */

/**
 * Class Doctor 医生出诊信息模型
 */
class VisitInfo extends Eloquent
{

    // 当前Model对应的数据库表 —— doctor
    protected $table = 'visit_info';


    // 设置表的主键
    protected $primaryKey = 'visit_id';

}