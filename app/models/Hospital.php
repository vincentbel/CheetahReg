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
class Hospital extends Eloquent
{
    // 当前Model对应的数据库表 —— hospital
    protected $table = 'hospital';

    // 设置hospital表的主键
    protected $primaryKey = 'hospital_id';

    /**
     * 与医院电话号表关联
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function phone()
    {
        return $this->hasMany('PhoneNumber');
    }

    /**
     * 与医院科室表关联
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public  function department()
    {
        return $this->hasMany('Department');
    }
    /**
     * 获取医院名称
     *
     * @return mixed
     */
    public function getHospitalName()
    {
        return $this->hospital_name;
    }

    /**
     * 获取医院id
     * @return mixed
     */
    public function getHospitalId()
    {
        return $this->hospital_id;
    }

    /**
     * 获取医院等级
     * @return mixed
     */
    public  function  getHospitalLevel()
    {
        return $this->level;
    }

    /**
     * 获取医院地址
     * @return mixed
     */
    public function getHospitalAddress()
    {

        $hospitalAddress =  \Cheetah\Services\Districts\District::getDetailDistrict($this->district_id).$this->address;
        return $hospitalAddress;
    }

    /**
     *  获取医院电话
     * @return mixed
     */
    public function getHospitalTel ()
    {
        $i = 0;
        $tel = array();
        foreach( $this->phone as $hospitalTel)
        {
            $tel[$i]= $hospitalTel ->phone_number;
            $i++;
        }
        return $tel;
    }

    /**
     * 获取医院网址
     * @return mixed
     */
    public function getHospitalUrl()
    {
        return $this->hospital_website;
    }

    /**
     * 获取医院简介
     * @return mixed
     */
    public function getHospitalIntroduction ()
    {
        return $this->introduction;
    }

    /**
     * 获取医院预约周期
     * @return mixed
     */
    public function  getHospitalReservationCycle ()
    {
        return $this->reservation_cycle;
    }

    /**
     * 获取医院放号时间
     * @return mixed
     */
    public function  getHospitalRegistrationOpenTime()
    {
        return $this->registration_open_time;
    }

    /**
     * 获取医院停挂时间
     * @return mixed
     */
    public function  getHospitalRegistrationClosedTime()
    {
        return $this->registration_closed_time;
    }

    /**
     * 获取医院退号时间
     * @return mixed
     */
    public function  getHospitalRegistrationCancelDeadline()
    {
        return $this->registration_cancel_deadline;
    }

    /**
     * 获取医院图片
     * @return mixed
     */
    public function getHospitalPicture()
    {
        return $this->picture;
    }

    /**
     * 获取医院科室信息
     * @return mixed|static
     */
    public function getHospitalDepartment ()
    {
        $department =$this->department->groupBy('department_category_name');
        $info = array();
        $i=0;
        foreach ($department as $d)
        {
            $info[$i] = $d;
            $i++;
        }
        return $info;
    }
    public function  getHospitalDepartmentLevelTwo ()
    {
        $department = $this->department;
        $i=0;
        $info = array();
        foreach($department as $d)
        {
            $info[$i]=array('department_name'=>$d->department_name,'department_id'=>$d->department_id);
            $i++;
        }
        return $info;
    }

    /**
     * 获取医院所在城市
     * @return mixed
     */
    public function  getHospitalCity ()
    {
        return $this->city;
    }

    /**
     * 医院访问量加1
     */
    public function increaseVisitorVolume ()
    {
        $this->visitor_volume ++;
        $this->save();
    }

    /**
     * 按照“医院等级”查询医院id
     * @param $hospitalLevel
     * @return array
     */
    public function getHospitalByLevel ($hospitalLevel)
    {
        $i = 0;
        $ids = array ();
        $hospitals = $this->where('level','=',$hospitalLevel)->get();
        foreach ($hospitals as $hospital)
        {
            $ids[$i] = $hospital -> hospital_id;
            $i++;
        }
        return $ids;
    }

    /**
     * 按城市名查询医院id
     * @param $districtId
     * @return array
     */
    public function getHospitalByDistrictIdAndLevel ($districtId,$level)
    {
        $districtId =  $districtId/10000;
        $districtId = $districtId.'%';
        if ($level != 0)
        {
            $hospitals = $this->where('district_id','like',$districtId)->where('level',$level)->get();
        }
        else
        {
            $hospitals = $this->where('district_id','like',$districtId)->get();
        }
        $i = 0;
        $ids = array ();
        foreach ($hospitals as $hospital)
        {
            $ids[$i] = $hospital -> hospital_id;
            $i++;
        }
        return $ids;
    }

    /**
     * 按医院名称返回医院ID
     * @param $hospitalName
     * @return mixed
     */
    public function getHospitalByHospitalName ($hospitalName)
    {
        $hospital = $this -> where('hospital_name','=',$hospitalName)->first();
        $id = $hospital->hospital_id;
        return $id;
    }

    /**
     * 获取热门医院
     * @return static
     */
    public function getTwoHotHospital ()
    {
        $hotHospitals = $this->orderBy('visitor_volume','DESC')->get()->take(2);
        $i = 0;
        $info = array();
        foreach($hotHospitals as $hospital)
        {
            $info[$i] = array('hospital_name'=>$hospital->hospital_name,'hospital_id'=>$hospital->hospital_id,
            'level'=>$hospital->level,'address'=>$hospital->address,'picture'=>$hospital->picture,'tel'=>$this->getHospitalTel());
            $i ++;
        }
        return $info;
    }
}