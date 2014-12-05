<?php
/**
 * Created by PhpStorm.
 * User: VincentBel
 * Date: 2014/12/3
 * Time: 9:54
 */

/**
 * Class Hospital
 * TODO hosptal 类，具体说明请自行补充
 *
 * @author XXX
 */
class Hospital
{
    // 当前Model对应的数据库表 —— user
    protected $table = 'hospital';

    // 设置hospital表的主键
    protected $primaryKey = 'hospital_id';


    /**
     * 获取医院名称
     * @param $id
     * @return mixed
     */
    public function getHospitalName($id)
    {
        $name = DB::table('hospital')->where('hospital_id', $id)->pluck('hospital_name');
        return $name;
    }


    /**
     * 获取医院等级
     * @param $id
     * @return mixed
     */
    public  function  getHospitalLevel($id)
    {
        $level = DB::table('hospital')->where('hospital_id', $id)->pluck('level');
        return $level;
    }

    /**
     * 获取医院地址
     * @param $id
     * @return mixed
     */
    public function getHospitalAddress($id)
    {
        $district_id = DB::table('hospital')->where('hospital_id', $id)->pluck('district_id');
        $district_name = DB::table('hospital')->where('hospital_id', $id)->pluck('district_name');
        /*
        $hospitalAddress = .$district_name;
        */
        return $hospitalAddress;
    }

    /**
     *  获取医院电话
     * @param $id
     * @return mixed
     */
    public function getHospitalTel ($id)
    {
        $tel= DB::table('hospital')->where('hospital_id', $id)->pluck('hospital_tel');
        return $tel;
    }

    /**
     * 获取医院网址
     * @param $id
     * @return mixed
     */
    public function getHospitalUrl($id)
    {
        $url = DB::table('hospital')->where('hospital_id', $id)->pluck('hospital_url');
        return $url;
    }

    /**
     * 获取医院简介
     * @param $id
     * @return mixed
     */
    public function getHospitalIntroduction ($id)
    {
        $introduction = DB::table('hospital')->where('hospital_id', $id)->pluck('introduction');
        return $introduction;
    }

    /**
     * 获取医院预约周期
     * @param $id
     * @return mixed
     */
    public function  getHospitalReservationCycle ($id)
    {
        $reservationCycle = DB::table('hospital')->where('hospital_id', $id)->pluck('reservation_cycle');
        return $reservationCycle;
    }

    /**
     * 获取医院放号时间
     * @param $id
     * @return mixed
     */
    public function  getHospitalRegistrationOpenTime($id)
    {
        $hospitalRegistrationOpenTime= DB::table('hospital')->where('hospital_id', $id)->pluck('registration_open_time');
        return $hospitalRegistrationOpenTime;
    }

    /**
     * 获取医院停挂时间
     * @param $id
     * @return mixed
     */
    public function  getHospitalRegistrationClosedTime($id)
    {
        $hospitalRegistrationClosedTime=DB::table('hospital')->where('hospital_id', $id)->pluck('registration_closed_time');
        return $hospitalRegistrationClosedTime;
    }

    /**
     * 获取医院退号时间
     * @param $id
     * @return mixed
     */
    public function  getHospitalRegistrationCancelDeadline($id)
    {
        $hospitalRegistrationCancelDeadline = DB::table('hospital')->where('hospital_id', $id)->pluck('registration_cancel_deadline');
        return $hospitalRegistrationCancelDeadline;
    }
}