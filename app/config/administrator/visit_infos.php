<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2014/12/23
 * Time: 22:35
 */

return array(

    'title' => '出诊信息',
    'single' => 'VisitInfo',
    'model' => 'VisitInfo',

    'columns' => array(
        'doctor_id' => array(
            'title' => '医生ID',
        ),
        'weekly' => array(
            'title' => '周几',
        ),
        'start_time' => array(
            'title' => '开始时间',
        ),
        'end_time' => array(
            'title' => '结束时间',
        ),
        'reservation_number' => array(
            'title' => '可挂号人数',
        ),
    ),

    'edit_fields' => array(
        'doctor_id' => array(
            'title' => '医生ID',
            'type' => 'number',
        ),
        'weekly' => array(
            'title' => '周几',
            'type' => 'enum',
            'options' => array( '0', '1', '2','3','4','5','6','7'),
        ),
        'start_time' => array(
            'title' => '开始时间',
            'type' => 'time',
            'time_format' => 'HH:mm',
        ),
        'end_time' => array(
            'title' => '结束时间',
            'type' => 'time',
            'time_format' => 'HH:mm',
        ),
        'reservation_number' => array(
            'title' => '可挂号人数',
            'type' => 'number',
        ),
    ),
);