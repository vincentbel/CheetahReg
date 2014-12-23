<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2014/12/22
 * Time: 14:38
 */

return array(

    'title' => '医院',
    'single' => 'Hospital',
    'model' => 'Hospital',

    'columns' => array(
        'hospital_name' => array(
            'title' => '医院名字',
        ),
        'picture' => array(
            'title' => '医院图片',
            'output' => '<img src="http://(:value)"  width="100px" />',
        ),
        'province' => array(
            'title' => '所在省市',
        ),
        'city' => array(
            'title' => '所在区县',
        ),
        'address' => array(
            'title' => '详细地址',
        ),
        'level' => array(
            'title' => '医院等级',
        ),
        'introduction' => array(
            'title' => '医院简介',
            'output' => '<div style=" width: 200px; ">
                            (:value)
                         </div>',
        ),
        'reservation_cycle' => array(
            'title' => '预约周期',
        ),
        'registration_open_time' => array(
            'title' => '放号时间',
        ),
        'registration_closed_time' => array(
            'title' => '停挂时间',
        ),
        'registration_cancel_deadline' => array(
            'title' => '退号时间',
        ),
        'credit_level_recover_cycle' => array(
            'title' => '信用等级恢复周期',
        ),
        'special_rule' => array(
            'title' => '特殊规则',
        ),
        'hospital_id' => array(
            'tittle' => 'hospital_id'
        ),
    ),

    'edit_fields' => array(
        'hospital_name' => array(
            'title' => '医院名字',
            'type' => 'text',
        ),
        'picture' => array(
            'title' => '医院图片',
            'type' => 'text',
        ),
        'province' => array(
            'title' => '所在省份',
            'type' => 'text',
        ),
        'city' => array(
            'title' => '所在区县',
            'type' => 'text',
        ),
        'address' => array(
            'title' => '详细地址',
            'type' => 'text',
        ),
        'level' => array(
            'title' => '医院等级',
            'type' => 'enum',
            'options' => array(
                '1'  ,
                '2'  ,
                '3'  ,
                '4'  ,
                '5'  ,
                '6'  ,
                '7'  ,
                '8'  ,
                '9'  ,
            ),
        ),
        'introduction' => array(
            'title' => '医院简介',
            'type'  =>  'textarea',
        ),
        'reservation_cycle' => array(
            'title' => '预约周期',
            'type'  =>  'number',
        ),
        'registration_open_time' => array(
            'title' => '放号时间',
            'type' => 'time',
            'time_format' => 'HH:mm',
        ),
        'registration_closed_time' => array(
            'title' => '停挂时间',
            'type' => 'time',
            'time_format' => 'HH:mm',
        ),
        'registration_cancel_deadline' => array(
            'title' => '退号时间',
            'type' => 'time',
            'time_format' => 'HH:mm',
        ),
        'credit_level_recover_cycle' => array(
            'title' => '信用等级回复周期',
            'type' => 'number',
        ),
        'special_rule' => array(
            'title' => '特殊规则',
            'type' => 'textarea',
        ),
        'hospital_id' => array(
            'title' => 'hospital_id',
            'type' => 'key',
        ),

    ),

);