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

    protected $fillable = array('announcement_id','admin_id', 'subject', 'message','author','post_time');

    public $error;

    // 验证规则
    public static $rules = [
        'subject' => 'required',
        'message' => 'required',
        'author' => 'required',
        'post_time' => 'required'
    ];

    public function isValid()
    {
        // 根据定下的rules验证个字段
        $validation = Validator::make($this->attributes, $this::$rules);
        if ($validation->fails()) {
            $this->error = $validation->messages();
            return false;
        }
        return true;
    }
}