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
       if (!is_null($results)) {
           $response['success'] = 1;
           $response['message'] = '管理员登录成功';
           \Session::put('admin',1);
       } else {
           $response['success'] = 0;
           $response['message'] = '用户名或者密码错误';
       }
       return Response::json($response);
   }
}