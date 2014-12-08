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

}