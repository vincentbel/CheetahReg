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

    /**
     * 根据预约状态数字返回具体预约信息
     *
     * @return string
     */
    public function getStatus()
    {
        $status = "";
        switch($this->reservation_status) {
            case 1:
                $status = "正在预约";
                break;
            case 2:
                $status = "已完成";
                break;
            case 3:
                $status = "已就诊";
                break;
            case 4:
                $status = "已取消";
                break;
            case 5:
                $status = "预约但未去就诊";
                break;
            default:
                $status = "不合法";
                break;
        }
        return $status;
    }

    /**
     * 判断订单是否可以取消
     *
     * @return bool
     */
    public function isReservationCancelable()
    {
        return ($this->reservation_status == 2);
    }

    /**
     * 取消预约订单,并将号源总数加一
     */
    public function cancelReservation()
    {
        $this->reservation_status = 4;
        $this->save();
        $reservationNumberInfo = ReservationNumberInfo::find($this->reservation_number_info_id);
        $reservationNumberInfo->remain_number++;
        $reservationNumberInfo->save();
    }

}