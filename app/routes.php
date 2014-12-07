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

/**
 * 用户注册入口
 */
Route::post('/register', 'UserController@register');


/**
 * add a route to test IdCardAndNameValidator class
 */
Route::get('/validateIdCardAndName/{idCardNumber}/{name}', function($idCardNumber, $name)
{
    $validator = new Cheetah\Services\Validation\IdCardAndNameValidator();
    return $validator->isIdCardAndNameMatched($idCardNumber, $name);
});


/**
 * add a route to test SMSValidator class
 */
Route::get('/validateSMS/{phoneNumber}', function($phoneNumber)
{
    $validator = new Cheetah\Services\Validation\SMSValidator();
    
    // 如果发送成功，返回json数据为：{"sendStatus": 1}；如果发送失败，返回json数据为：{"sendStatus":0}
    if ($validator->sendSMS($phoneNumber)) {
    	$arr = Array('sendStatus'=>'1');
    	echo json_encode($arr);
    } else {
    	$arr = Array('sendStatus'=>'0');
    	echo json_encode($arr);
    }
});

/**
 * 显示医院信息路线
 */
Route::get('/hospital/{hospitalId}','HospitalController@getHospitalInfo');

/**
 * 返回一级地区列表
 */
Route::post('/districtOne', function()
{
    return \Cheetah\Services\Districts\District::scopeLevelOne();
});

/**
 * 返回二级地区列表
 */
Route::post('/districtTwo', function()
{
    return \Cheetah\Services\Districts\District::scopeLevelTwo(Input::get('district_id'));
});

/**
 * 返回三级地区列表
 */
Route::post('/districtThree', function()
{
    return \Cheetah\Services\Districts\District::scopeLevelThree(Input::get('district_id'));
});


/**
 * 通过三级行政地区的id查询该地区的完整地区信息
 */
Route::post('/detailDistrict', function()
{
    return \Cheetah\Services\Districts\District::getDetailDistrict(Input::get('district_id'));
});