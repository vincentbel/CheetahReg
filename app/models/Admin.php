<?php
/**
 * Created by PhpStorm.
 * User: VincentBel
 * Date: 2014/12/8
 * Time: 9:01
 */

/**
 * Class Admin 管理员模型
 */
class Admin extends Eloquent
{

    // 当前Model对应的数据库表 —— admin
    protected $table = 'admin';


    // 设置表的主键
    protected $primaryKey = 'admin_id';

}