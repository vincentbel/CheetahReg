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

    /**
     * 根据科室名称获取科室id
     * @param $departmentName
     * @return array
     */
    public function getDepartmentIdByDepartmentName ($departmentName)
    {
        $department = $this -> where('department_name','=',$departmentName)->get();
        $i = 0;
        $departmentId = array();
        foreach($department as $d)
        {
            $departmentId[$i] = $d->department_id;
            $i++;
        }
        return $departmentId;
    }

    /**
     * 通过医院名称获取科室
     * @param $departmentName
     * @return array
     */
    public function getHospitalByDepartment($departmentName)
    {
        $departmentId = $this->getDepartmentIdByDepartmentName($departmentName);
        $i = 0;
        $hospitals= array();
        foreach( $departmentId as $id)
        {
            $department = $this->find($id);
            $hospital = $department->hospital;
            $hospitals[$i] = array('department_id'=>$id,'name'=>$hospital->hospital_name,'level'=>$hospital->level);
            $i++;
        }
        return $hospitals;
    }
}
