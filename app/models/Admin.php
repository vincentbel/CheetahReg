<?php
/**
 * Created by PhpStorm.
 * User: VincentBel
 * Date: 2014/12/8
 * Time: 9:01
 */

/**
 * Class Admin 管理员模型
 */
class Admin extends Eloquent
{
    // 当前Model对应的数据库表 —— admin
    protected $table = 'admin';

    // 设置表的主键
    protected $primaryKey = 'admin_id';

    protected $fillable = array('admin_id', 'username', 'password');

    // 验证规则
    public static $rules = [
        'username' => 'required',
        'password' => 'required|min:6'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * 管理员和公告是一对多的关系
     */
    public function announcements()
    {
        return $this->hasMany('Announcement');
    }



}