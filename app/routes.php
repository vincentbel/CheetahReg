<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

Route::get('/check', function()
{

    if(DB::connection('mysql')->getDatabaseName())
    {
        echo "conncted sucessfully to database ".DB::connection()->getDatabaseName();
    }

});



/*---------------------------------------------------------
 * 用户相关route
 * --------------------------------------------------------
 */

/**
 * 用户注册入口
 */
Route::post('/register', 'UserController@register');

/**
 * 用户登录
 */
Route::post('/login', 'UserController@login');

/**
 * 用户个人中心，只有登录的用户才能进入，未登录的用户将转到登录页面
 */
Route::get('/profile', array('before' => 'auth', 'uses' => 'UserController@showProfile'));




/*---------------------------------------------------------
 * 医院相关route
 * --------------------------------------------------------
 */


/**
 * 显示医院信息路线
 */
Route::get('/hospital/{hospitalId}','HospitalController@getHospitalInfo');




/*---------------------------------------------------------
 * 工具相关route
 * --------------------------------------------------------
 */


/**
 * add a route to test IdCardAndNameValidator class
 */
Route::get('/validateIdCardAndName/{idCardNumber}/{name}', function($idCardNumber, $name)
{
    if (\Cheetah\Services\Validation\IdCardAndNameValidator::isIdCardAndNameMatched($idCardNumber, $name)) {
        return Response::json(array(
            'success' => 1,
        ));
    } else {
        return Response::json(array(
            'success' => 0,
        ));
    }
});


/**
 * 验证手机号是否被注册，如果未被注册则发送验证码
 */
Route::get('/validateSMS/{phoneNumber}', function($phoneNumber)
{
    // 验证手机号是否为11位并且不存在于user表中
    $validator = Validator::make(array('mobile_number' => $phoneNumber), array('mobile_number' => 'phone|unique:user'));

    if ($validator->fails()) {
        // 验证失败，返回错误信息

        return Response::json(array(
            'sendStatus' => 0,
            'message' => $validator->messages(),
        ));
    }

    $smsValidator = new Cheetah\Services\Validation\SMSValidator();

    // 如果发送成功，返回json数据为：{"sendStatus": 1}；如果发送失败，返回json数据为：{"sendStatus":0}
    if ($smsValidator->sendSMS($phoneNumber)) {
    	$response = array('sendStatus'=>'1');
    } else {

    	$response = array(
            'sendStatus'=>'0',
            'message' => $smsValidator->getMessages()
        );
    }

    return Response::json($response);
});

/**
 * 返回一级地区列表
 */
Route::post('/districtOne', function()
{
    $response = Response::json(\Cheetah\Services\Districts\District::scopeLevelOne());
    return $response;
});

/**
 * 返回二级地区列表
 */
Route::post('/districtTwo', function()
{
    $response = Response::json(\Cheetah\Services\Districts\District::scopeLevelTwo(Input::get('district_id')));
    return $response;
});

/**
 * 返回三级地区列表
 */
Route::post('/districtThree', function()
{
    $response = Response::json(\Cheetah\Services\Districts\District::scopeLevelThree(Input::get('district_id')));
    return $response;
});


/**
 * 通过三级行政地区的id查询该地区的完整地区信息
 */
Route::post('/detailDistrict', function()
{
    return \Cheetah\Services\Districts\District::getDetailDistrict(Input::get('district_id'));
});