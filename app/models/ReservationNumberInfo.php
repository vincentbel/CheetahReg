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

    /**
     *  号源信息表 reservation_number_info 和 联系人表 contact_people  的关系是多对多关系
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function contactPeoples()
    {
        return $this->belongsToMany('ContactPeople', 'reservation')->withPivot('reservation_status', 'sequence_number', 'attendance');
    }
}
