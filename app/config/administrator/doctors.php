<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2014/12/23
 * Time: 21:11
 */
return array(

    'title' => '医生',
    'single' => 'Doctor',
    'model' => 'Doctor',

    'columns' => array(
        'name' => array(
            'title' => '姓名',
        ),
        'department_id' => array(
            'title' => '所属科室ID',
        ),
        'professional_title' => array(
            'title' => '职称',
        ),
        'speciality' => array(
            'title' => '专长',
        ),
        'reservation_fee' => array(
            'title' => '挂号费',
        ),
        'doctor_id' => array(
            'title' => '医生ID',
        ),
    ),

    'edit_fields' => array(
        'name' => array(
            'title' => '姓名',
            'type' => 'text',
        ),
        'department_id' => array(
            'title' => '所属科室ID',
            'type' => 'number',
        ),
        'professional_title' => array(
            'title' => '职称',
            'type' => 'enum',
            'options' => array(
                '见习医师' ,
                '副主治医师' ,
                '主治医师' ,

            ),
        ),
        'speciality' => array(
            'title' => '专长',
            'type' => 'text',
        ),
        'reservation_fee' => array(
            'title' => '挂号费',
            'type' => 'number',
        ),
        'doctor_id' => array(
            'title' => '医生ID',
            'type' => 'key',
        ),
    ),
);