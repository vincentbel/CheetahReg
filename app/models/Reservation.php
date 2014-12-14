<?php
/**
 * Created by PhpStorm.
 * User: VincentBel
 * Date: 2014/12/8
 * Time: 9:13
 */

/**
 * Class Reservation 预约信息模型
 */
class Reservation extends Eloquent
{

    // 当前Model对应的数据库表
    protected $table = 'reservation';


    // 设置表的主键
    protected $primaryKey = 'reservation_id';

}