<?php
class District extends Eloquent {

    /**
     * The database table used by the model
     *
     * @var string
     */
    protected $table = 'district';

    /**
     * Disabled auto timestamps
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 查询一级行政区
     *
     * @param $query
     * @return mixed
     */
    public function scopeLevelOne($query)
    {
        return $query->select('district_id', 'district_name')->where('parent_id', '=', '0');
    }

    /**
     * 通过一级行政区的id查询二级行政区
     *
     * @param $query
     * @param $district_id
     * @return mixed
     */
    public function scopeLevelTwo($query, $district_id)
    {
        return $query->select('district_id', 'district_name')->where('parent_id', '=', $district_id);
    }

    /**
     * 通过二级行政区的id查询三级行政区
     *
     * @param $query
     * @param $district_id
     * @return mixed
     */
    public function scopeLevelThree($query, $district_id)
    {
        return $query->select('district_id', 'district_name')->where('parent_id', '=', $district_id);
    }
}
