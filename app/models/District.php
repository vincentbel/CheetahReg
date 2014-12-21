<?php

class District {

  /**
     * The database table used by the model
     *
     * @var string
     */
    protected $table ;


    /**
     * 查询一级行政区
     *
     * @param $query
     * @return mixed
     */
    public function scopeLevelOne()
    {
        return DB::table('district')->select('district_id', 'district_name')->where('parent_id', '=', '0')->get();
    }

    /**
     * 通过一级行政区的id查询二级行政区
     *
     * @param $query
     * @param $district_id
     * @return mixed
     */
    public function scopeLevelTwo($districtId)
    {
        return DB::table('district')->select('district_id', 'district_name')->where('parent_id', '=', $districtId)->get();
    }

    /**
     * 通过二级行政区的id查询三级行政区
     *
     * @param $query
     * @param $district_id
     * @return mixed
     */
    public function scopeLevelThree($districtId)
    {
        return DB::table('district')->select('district_id', 'district_name')->where('parent_id', '=', $districtId)->get();
    }

    /**
     * 通过三级行政地区的id查询该地区的完整地区信息
     *
     * @param $district_id
     * @return string
     */
    public function getDetailDistrict($districtId)
    {
        if (!$this->isLevelThree($districtId)){
            return '该行政地区不是三级行政地区, 不能通过此查询完整的地区信息';
        }

        $levelThree = DB::table('district')->where('district_id', '=', $districtId)->first();
        $levelTwo = DB::table('district')->where('district_id', '=', $levelThree->parent_id)->first();
        $levelOne = DB::table('district')->where('district_id', '=', $levelTwo->parent_id)->first();

        $detailDistrict = $levelOne->district_name.$levelTwo->district_name.$levelThree->district_name;

        return $detailDistrict;
    }

    /**
     * 通过城市名称获取对应的地区id
     * @param $city
     * @return mixed|static
     */
    public function getLevelOneByCity($city)
    {
        $c = DB::table('district')->where('district_name', '=', $city)->first();
        $levelOne = $c->district_id;
        return $levelOne;
    }

    /**
     * 通过三级行政地区的id查询该地区所在城市
     *
     * @param $districtId
     * @return string
     */
    public function getCityName($districtId)
    {
        if (!$this->isLevelThree($districtId)){
            return '该行政地区不是三级行政地区, 不能通过此查询所在城市';
        }

        $levelThree = DB::table('district')->where('district_id', '=', $districtId)->first();
        $levelTwo = DB::table('district')->where('district_id', '=', $levelThree->parent_id)->first();

        if ($levelTwo->district_name == '县' || $levelTwo->district_name == '市辖区'){
            $levelOne = DB::table('district')->where('district_id', '=', $levelTwo->parent_id)->first();
            return $levelOne->district_name;
        } else {
            return $levelTwo->district_name;
        }
    }

    /**
     * 通过行政地区的id查询该行政地区是否为三级行政地区
     *
     * @param $districtId
     * @return bool
     */
    public function isLevelThree($districtId)
    {
        $level = DB::table('district')->where('district_id', '=', $districtId)->pluck('level');

        if ($level == '3') {
            return true;
        } else {
            return false;
        }
    }
}
