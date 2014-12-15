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
    var_dump($validator->isIdCardAndNameMatched($idCardNumber, $name));
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
 * 按“医院等级”返回医院信息
 */
Route::get('/hospital_level/{hospitalLevel}','HospitalController@getHospitalByLevel');
/**
 * 按“医院地区”返回医院信息
 */
Route::get('/hospital_district/{city}','HospitalController@getHospitalByCity');
/**
 * 按“医院地区”返回医院名称
 */
Route::get('/hospital_name/{city}','HospitalController@getHospitalNameByCity');
/**
 * 按“医院名称”返回医院科室
 */
Route::get('/hospital_department/{hospitalName}','HospitalController@getDepartmentByHospitalName');
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