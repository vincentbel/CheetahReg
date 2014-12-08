<?php
/**
 * Created by PhpStorm.
 * User: VincentBel
 * Date: 2014/12/8
 * Time: 9:03
 */

/**
 * Class Announcement 公告模型
 */
class Announcement extends Eloquent
{

    // 当前Model对应的数据库表 —— announcement
    protected $table = 'announcement';


    // 设置表的主键
    protected $primaryKey = 'announcement_id';

}