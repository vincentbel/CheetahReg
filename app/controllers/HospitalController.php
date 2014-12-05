<?php
/**
 * Created by PhpStorm.
 * User: VincentBel
 * Date: 2014/12/3
 * Time: 9:42
 */

/**
 * Class HospitalController
 * 医院信息相关的控制类
 *
 * @author
 */
class HospitalController extends BaseController
{

    /**
     * 获取医院页面的所有信息并返回
     */
    function getHospitalInfo($hospitalId)
    {
        $hospital = new Hospital();
        // 医院名称
        $hospitalName = $hospital->getHospitalName($hospitalId);

        // 医院等级
        $hospitalLevel = $hospital->getHospitalLevel($hospitalId);

        // 医院地址
        $hospitalAddress = $hospital->getHospitalAddress($hospitalId);

        // 医院电话
        $hospitalTel = $hospital->getHospitalTel($hospitalId);

        // 医院网址
        $hospitalUrl = $hospital->getHospitalUrl($hospitalId);

        // 医院简介
        $hospitalIntroduction = $hospital->getHospitalIntroduction($hospitalId);

        // 医院预约周期
        $hospitalReservationCycle = $hospital->getHospitalReservationCycle($hospitalId);

        // 医院放号时间
        $hospitalRegistrationOpenTime =  $hospital->getHospitalRegistrationOpenTime($hospitalId);

        // 医院停挂时间
        $hospitalRegistrationClosedTime = $hospital->getHospitalRegistrationClosedTime($hospitalId);

        // 医院退号时间
        $hospitalRegistrationCancelDeadline = $hospital->getHospitalRegistrationCancelDeadline($hospitalId);

        $hospitalInformation = array('name'=>$hospitalName,'level'=>$hospitalLevel,'address'=>$hospitalAddress,
            'tel'=>$hospitalTel,'url'=>$hospitalUrl,'introduction'=>$hospitalIntroduction,'reservation_cycle'=>$hospitalReservationCycle,
            'registration_open_time'=>$hospitalRegistrationOpenTime,'registration_closed_time'=>$hospitalRegistrationClosedTime,
            'registration_cancel_deadline'=>$hospitalRegistrationCancelDeadline);

        return json_encode($hospitalInformation);
    }
}