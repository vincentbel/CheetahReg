<?php
/**
 * Author: VincentBel
 * Date: 2014/12/14
 * Time: 20:11
 */

return array(

    'title' => '用户',
    'single' => 'User',
    'model' => 'User',

    'columns' => array(
        'ID_card_number' => array(
            'title' => '身份证',
        ),
        'real_name' => array(
            'title' => '姓名',
        ),
        'gender' => array(
            'title' => '性别',
        ),
        'credit_level' => array(
            'title' => '信用等级',
        ),
        'mobile_number' => array(
            'title' => '手机号',
        ),
        'detail_address' => array(
            'title' => '详细地址',
        ),
    ),

    'edit_fields' => array(
        'ID_card_number' => array(
            'title' => '身份证',
            'type' => 'text',
        ),
        'real_name' => array(
            'title' => '姓名',
            'type' => 'text',
        ),
        'password' => array(
            'title' => '密码',
            'type' => 'password',
        ),
        'gender' => array(
            'title' => '性别',
            'type' => 'enum',
            'options' => array(
                '0' ,
                '1' ,
                '2' ,

            ),
        ),
        'credit_level' => array(
            'title' => '信用等级',
            'type' => 'enum',
            'options' => array(
                '0' ,
                '1' ,
                '2' ,
                '3' ,

            ),
        ),
        'mobile_number' => array(
            'title' => '手机号',
            'type' => 'number',
        ),
        'detail_address' => array(
            'title' => '详细地址',
            'type' => 'text',
        ),
    ),
);