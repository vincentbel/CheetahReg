<?php

namespace District;

class District {

  /**
     * The database table used by the model
     *
     * @var string
     */
    protected $table ;

    public function __construct()
    {
        $this->table = DB::table('district');
    }

    /**
     * 查询一级行政区
     *
     * @param $query
     * @return mixed
     */
    public function scopeLevelOne()
    {
        return $this->table->select('district_id', 'district_name')->where('parent_id', '=', '0')->get();
    }

    /**
     * 通过一级行政区的id查询二级行政区
     *
     * @param $query
     * @param $district_id
     * @return mixed
     */
    public function scopeLevelTwo($district_id)
    {
        return $this->table->select('district_id', 'district_name')->where('parent_id', '=', $district_id)->get();
    }

    /**
     * 通过二级行政区的id查询三级行政区
     *
     * @param $query
     * @param $district_id
     * @return mixed
     */
    public function scopeLevelThree($district_id)
    {
        return $this->table->select('district_id', 'district_name')->where('parent_id', '=', $district_id)->get();
    }
}
