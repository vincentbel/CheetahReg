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
    public function getDepartmentLevelTwo($department_id)
    {
        $departments = DepartmentCategory::where('parent_id', '=', $department_id)->get();
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
    public function getDepartmentLevelTwoDetail($department_id)
    {
        $department = Response::json(DepartmentCategory::where('department_id', '=', $department_id)->first());
        return $department;
    }


    /**
     * 根据医院二级科室的id和日期查询并返回其号源信息
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReservationNumberInfo()
    {
        $department_id = Input::get('department_id');
        $date = Input::get('date');
        $hospital_id = Department::where('department_id', '=', $department_id)->pluck('hospital_id');


        $response['registration_info'] = Hospital::select('reservation_cycle', 'registration_open_time',
                        'registration_closed_time', 'registration_cancel_deadline', 'special_rule')
                        ->where('hospital_id', '=', $hospital_id)->first();
        $response['registration_detail'] = ReservationNumberInfo::where('department_id', '=', $department_id)
                    ->where('date', '=', $date)->get();

        return $response;
    }

    /**
     * 通过二级科室名称获取拥有该科室的医院的数量
     *
     * @param $department_name
     * @return int
     */
    public function getHospitalNumberByDepartmentName($department_name)
    {
        return Department::where('department_name', '=', $department_name)->count('hospital_id');
    }
}
