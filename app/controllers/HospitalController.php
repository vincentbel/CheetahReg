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
        // 医院model
        $hospital = Hospital::find($hospitalId);

        // 访问量加1
        $hospital->increaseVisitorVolume();

        // 医院id
        $hospitalId = $hospital -> getHospitalId();

        // 医院名称
        $hospitalName = $hospital->getHospitalName();

        // 医院等级
        $hospitalLevel = $hospital->getHospitalLevel();

        // 医院地址
        $hospitalAddress = $hospital->getHospitalAddress();

        // 医院电话
        $hospitalTel = $hospital->getHospitalTel();

        // 医院网址
        $hospitalUrl = $hospital->getHospitalUrl();

        // 医院简介
        $hospitalIntroduction = $hospital->getHospitalIntroduction();

        // 医院预约周期
        $hospitalReservationCycle = $hospital->getHospitalReservationCycle();

        // 医院放号时间
        $hospitalRegistrationOpenTime =  $hospital->getHospitalRegistrationOpenTime();

        // 医院停挂时间
        $hospitalRegistrationClosedTime = $hospital->getHospitalRegistrationClosedTime();

        // 医院退号时间
        $hospitalRegistrationCancelDeadline = $hospital->getHospitalRegistrationCancelDeadline();

        // 医院图片
        $hospitalPicture = $hospital->getHospitalPicture();

        // 医院科室信息查询
        $hospitalDepartmentName = $hospital->getHospitalDepartment();

        $hospitalInformation = array('hospital_id'=>$hospitalId,'name'=>$hospitalName,'level'=>$hospitalLevel,'address'=>$hospitalAddress,
            'tel'=>$hospitalTel,'url'=>$hospitalUrl,'introduction'=>$hospitalIntroduction,'reservation_cycle'=>$hospitalReservationCycle,
            'registration_open_time'=>$hospitalRegistrationOpenTime,'registration_closed_time'=>$hospitalRegistrationClosedTime,
            'registration_cancel_deadline'=>$hospitalRegistrationCancelDeadline,'picture'=>$hospitalPicture,
            'department_name'=>$hospitalDepartmentName);

        return json_encode($hospitalInformation);
    }

    /**
     * 按照“医院等级”查询医院简略信息
     * @param $hospitalLevel
     * @return string
     */
    function getHospitalByLevel ($hospitalLevel)
    {
        $hospital = new Hospital();
        $hospital_ids = $hospital->getHospitalByLevel($hospitalLevel);
        $hospitalInfo = array();
        $i = 0;
        foreach ($hospital_ids as $id)
        {
            $h = Hospital::find($id);
            // 医院id
            $hospitalId = $h -> getHospitalId();
            // 医院名称
            $hospitalName = $h->getHospitalName();
            // 医院等级
            $hospitalLevel = $h->getHospitalLevel();
            // 医院电话
            $hospitalTel = $h->getHospitalTel();
            // 医院地址
            $hospitalAddress = $h->getHospitalAddress();
            $hospitalInfo[$i] = array('hospital_id'=>$hospitalId,'name'=>$hospitalName,'level'=>$hospitalLevel, 'tel'=>$hospitalTel,
                'address'=>$hospitalAddress);
            $i ++;
        }
        $information = array('number'=>$i,'hospital'=>$hospitalInfo);
        return json_encode($information);
    }

    /**
     * 按照城市查询医院简略信息
     * @param $city
     * @return string
     */
    function getHospitalByCityAndLevel($city,$level)
    {
        $hospital = new Hospital();
        $districtId =  \Cheetah\Services\Districts\District::getLevelOneByCity($city);
        $hospitalIds = $hospital->getHospitalByDistrictIdAndLevel($districtId,$level);
        $hospitalInfo = array();
        $i = 0;
        foreach ($hospitalIds as $id)
        {
            $h = Hospital::find($id);
            // 医院id
            $hospitalId = $h -> getHospitalId();
            // 医院名称
            $hospitalName = $h->getHospitalName();
            // 医院等级
            $hospitalLevel = $h->getHospitalLevel();
            // 医院电话
            $hospitalTel = $h->getHospitalTel();
            // 医院地址
            $hospitalAddress = $h->getHospitalAddress();
            $hospitalInfo[$i] = array('hospital_id'=>$hospitalId,'name'=>$hospitalName,'level'=>$hospitalLevel, 'tel'=>$hospitalTel,
                'address'=>$hospitalAddress);
            $i++;
        }
        $information = array ('number'=>$i,'city'=>$city,'level_all'=>$level,'hospital'=>$hospitalInfo);
        return json_encode($information);
    }

    /**
     * 根据城市查询医院名称
     * @param $city
     * @return array
     */
    function getHospitalNameByCity($city)
    {
        $hospital = new Hospital();
        $districtId = \Cheetah\Services\Districts\District::getLevelOneByCity($city);
        $hospitalIds = $hospital->getHospitalByDistrictId($districtId);
        $hospitalInfo = array();
        $i = 0;
        foreach ($hospitalIds as $id)
        {
            $h = Hospital::find($id);
            $hospitalInfo[$i]=$h->getHospitalName();
            $i ++;
        }
        $information = array('name'=>$hospitalInfo);
        return json_encode($information);
    }

    /**
     * 根据医院名称返回科室名称
     * @param $hospitalName
     * @return string
     */
    function getDepartmentByHospitalName($hospitalName)
    {
        $hospital = new Hospital();
        $id = $hospital->getHospitalByHospitalName($hospitalName);

        $h = Hospital::find($id);
        $hospitalDepartment = $h->getHospitalDepartment();
        return json_encode($hospitalDepartment);
    }

    /**
     * 获取访问量最多的两家医院
     * @return string
     */
    function getHotHospital ()
    {
        $hospital = new Hospital();
        $hospitals = $hospital -> getTwoHotHospital();
        $information = array('hot_hospital'=>$hospitals);
        return json_encode($information);
    }
}