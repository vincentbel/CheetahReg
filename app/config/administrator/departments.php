<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2014/12/23
 * Time: 18:14
 */

return array(

    'title' => '科室',
    'single' => 'Department',
    'model' => 'Department',

    'columns' => array(
        'department_name' => array(
            'title' => '科室名称',
        ),
        'contact_people' => array(
            'title' => '科室联系人',
        ),
        'department_number' => array(
            'title' => '科室电话',
        ),
        'department_category_name' => array(
            'title' => '科室类型',
        ),
        'hospital_id' => array(
            'title' => '所属医院ID',
        ),
        'department_id' => array(
            'tittle' => 'hospital_id'
        ),
    ),

    'edit_fields' => array(
        'department_name' => array(
            'title' => '科室名称',
            'type' => 'text',
        ),
        'contact_people' => array(
            'title' => '科室联系人',
            'type' => 'text',
        ),
        'department_number' => array(
            'title' => '科室电话',
            'type' => 'number',
        ),
        'department_category_name' => array(
            'title' => '科室类型',
            'type' => 'text',
        ),
        'hospital_id' => array(
            'title' => '所属医院ID',
            'type' => 'number',
        ),
        'department_id' => array(
            'title' => 'department_id',
            'type' => 'key',
        ),

    ),
);