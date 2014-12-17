<?php
/**
 * Author: VincentBel
 * Date: 2014/12/17
 * Time: 18:25
 */

/**
 * Class ReservationNumberInfo
 * 号源信息类
 */
class ReservationNumberInfo extends Eloquent
{
    // 当前Model对应的数据库表
    protected $table = 'reservation_number_info';


    // 设置表的主键
    protected $primaryKey = 'reservation_number_info_id';

    // 数据库表中不需要created_at 和update_at 信息
    public $timestamps = false;
}
