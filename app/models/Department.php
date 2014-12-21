<?php
/**
 * Created by PhpStorm.
 * User: VincentBel
 * Date: 2014/12/8
 * Time: 8:57
 */

/**
 * Class Department 科室模型
 */
class Department extends Eloquent
{

    // 当前Model对应的数据库表 —— department
    protected $table = 'department';


    // 设置 department 表的主键
    protected $primaryKey = 'department_id';

    /**
     * 与Hospital表关联
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hospital()
    {
        return $this -> belongsTo('Hospital');
    }

}
