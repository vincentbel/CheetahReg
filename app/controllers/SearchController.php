<?php

class SearchController extends BaseController{

    /**
     * The controller for search feature
     */

    /**
     * 搜索医院名称或科室名称, 返回医院信息数组
     *
     * @return array
     */
    public function search()
    {
        $string = Input::get('keywords');
        $keywords = explode(' ', $string, 5);
        $hospitalIds = array();

        foreach($keywords as $keyword)
        {
            $hospitalIdsHospName = $this->searchByHospitalName($keyword);
            $hospitalIdsDeptName = $this->searchByDepartmentName($keyword);

            $hospitalIds = array_merge($hospitalIds, $hospitalIdsHospName, $hospitalIdsDeptName);
        }

        if ($hospitalIds)
        {
            $hospitalInfo = Hospital::whereIn('hospital_id', $hospitalIds)->get()->toJson();
        } else
        {
            $hospitalInfo['message'] = "对不起, 找不到含关键字 $string 的医院. 请重新输入关键字搜索" ;
        }

        return $hospitalInfo;
    }


    /**
     * 通过医院名称搜索返回医院id数组
     *
     * @param $keyword
     * @return array
     */
    public function searchByHospitalName($keyword)
    {
        return Hospital::where('hospital_name', 'LIKE', "%$keyword%")->select('hospital_id')->get()->toArray();
    }


    /**
     * 通过科室名称搜索返回医院id数组
     *
     * @param $keyword
     * @return array
     */
    public function searchByDepartmentName($keyword)
    {
        return Department::where('department_name', 'LIKE', "%$keyword%")->select('hospital_id')->get()->toArray();
    }

}
