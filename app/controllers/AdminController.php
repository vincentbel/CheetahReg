<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2014/12/12
 * Time: 23:40
 */

class AdminController extends BaseController{

    protected $admin;

    /**
     * @param Admin $admin
     */
    function __construct(Admin $admin)
    {
        $this->admin = $admin;
    }

   public function login()
   {
       // 管理员登录的用户名
       $username= Input::get('username');

       // 登录密码
       $password = Input::get('password');

       // 返回的信息
       $response = array();
       $results = DB::select('select * from admin where username = ? and password = ?', array($username,$password));
       if (!empty($results)) {
           \Session::put('admin',1);
           return  Redirect::to('/admin');
       }
        //return Redirect::to('/adminLogin')->withError('用户名或者密码错误');
   }
}