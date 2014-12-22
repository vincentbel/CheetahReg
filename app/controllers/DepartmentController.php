<?php

class DepartmentController extends BaseController{
    /**
     * 医院科室控制类, 主要处理按科室预约页面的请求
     */

    /**
     * 返回医院一级科室列表
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDepartmentLevelOne()
    {
        $department = Response::json(DepartmentCategory::where('level', '=', '1')->get());
        return $department;
    }

    /**
     * 根据医院一级科室的id查询并返回其二级科室
     *
     * @param $department_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDepartmentLevelTwo($departmentId)
    {
        $departments = DepartmentCategory::where('parent_id', '=', $departmentId)->get();
        foreach ($departments as $department)
        {
            $department['hospital_number'] = $this->getHospitalNumberByDepartmentName($department['chinese_name']);
        }
        $departments->toJson();

        return $departments;
    }

    /**
     * 根据医院二级科室的id查询并返回其详细信息
     *
     * @param $department_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDepartmentLevelTwoDetail($departmentId)
    {
        $department = Response::json(DepartmentCategory::where('department_id', '=', $departmentId)->first());
        return $department;
    }


    /**
     * 根据医院二级科室的id和日期查询并返回其号源信息
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReservationNumberInfo()
    {
        $departmentId = Input::get('department_id');
        $date = Input::get('date');

        $response = ReservationNumberInfo::where('department_id', '=', $departmentId)
                    ->where('date', '=', $date)->get();

        foreach($response as $reservationNumInfo)
        {
            $reservationNumInfo['time_interval'] = $reservationNumInfo['start_time']. ' - '. $reservationNumInfo['end_time'];
            $reservationNumInfo['level'] = Doctor::where('doctor_id', '=', $reservationNumInfo['doctor_id'])->first()
                                           ->getAttribute('professional_title');
            $reservationNumInfo['department_name'] = Department::where('department_id', '=', $reservationNumInfo['department_id'])
                                                     ->first()->pluck('department_name');
            $hospitalId = Department::where('department_id', '=', $reservationNumInfo['department_id'])->first()
                        ->pluck('hospital_id');
            $reservationNumInfo['hospital_name'] = Hospital::where('hospital_id', '=', $hospitalId)->first()
                                                 ->pluck('hospital_name');
        }

        return $response->toJson();
    }


    /**
     * 返回特定科室的信息, 包括预约开始时间, 预约结束时间等
     *
     * @return array
     */
    public function getDepartmentInfo()
    {
        $departmentId = Input::get('department_id');
        $hospitalId = Department::where('department_id', '=', $departmentId)->pluck('hospital_id');

        $response = Hospital::select('reservation_cycle', 'registration_open_time',
                        'registration_closed_time', 'registration_cancel_deadline', 'special_rule')
                        ->where('hospital_id', '=', $hospitalId)->first();

        $response['registration_open_time'] = substr($response['registration_open_time'], 0, 5);
        $response['registration_closed_time'] = substr($response['registration_closed_time'], 0, 5);
        $response['registration_cancel_deadline'] = substr($response['registration_cancel_deadline'], 0, 5);
        

        if (! $response)
        {
            $response['message'] = '对不起, 找不到相关科室信息.';
        }

        return $response;
    }

    /**
     * 通过二级科室名称获取拥有该科室的医院的数量
     *
     * @param $department_name
     * @return int
     */
    public function getHospitalNumberByDepartmentName($departmentName)
    {
        return Department::where('department_name', '=', $departmentName)->count('hospital_id');
    }

    /**
     * 通过二级科室类别id和地区名获取相关医院信息
     *
     * @return \Illuminate\Database\Eloquent\Collection|string|static[]
     */
    public function getHospitalInfo()
    {
        $departmentCategoryId = Input::get('department_id');
        $districtName = Input::get('district_name');
        $departmentName = DepartmentCategory::where('department_id', '=', $departmentCategoryId)->pluck('chinese_name');
        $hospitalIds = Department::where('department_name', '=', $departmentName)->select('hospital_id')
                       ->get()->toArray();
        $hospitalInfo = '';

        if ($hospitalIds)
        {
            $hospitalInfo = Hospital::whereIn('hospital_id', $hospitalIds)->where('province', 'LIKE', "%$districtName%")
                            ->orWhere('city', 'LIKE', "%$districtName%")->select('hospital_id', 'hospital_name', 'level')->get();

            if (!$hospitalInfo->isEmpty())
            {
                foreach ($hospitalInfo as $hospital)
                {
                    $hospital['department_id'] = Department::where('department_name', '=', $departmentName)
                        ->where('hospital_id', '=', $hospital['hospital_id'])->pluck('department_id');
                }
            } else
            {
                $hospitalInfo['message'] = '对不起, 找不到相关医院';
            }
        }


        return $hospitalInfo;
    }

    public function  getHospitalByDepartment ($departmentName)
    {
        $department = new Department();
        $hospital = $department->getHospitalByDepartment($departmentName);
        return json_encode($hospital);
    }
}
