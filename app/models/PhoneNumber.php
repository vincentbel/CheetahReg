<?php
/**
 * Created by PhpStorm.
 * User: Sunyao_Will
 * Date: 14/12/08
 * Time: 17:07
 */

class PhoneNumber extends Eloquent
{
    // 当前Model对应的数据库表 —— phone_number
    protected $table = 'phone_number';

    // 设置hospital表的主键
    protected $primaryKey = 'phone_number_id';
} 