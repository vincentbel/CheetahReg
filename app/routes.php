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
 * 判断一个用户是否登录
 */
Route::get('/isUserLoggedIn', function()
{
    $response = array();
    $response['loggedIn'] = Auth::check() ? 1 : 0;
    return Response::json($response);
});

/**
 * 用户个人中心，只有登录的用户才能进入，未登录的用户将转到登录页面
 */
Route::get('/profile', array('before' => 'auth', 'uses' => 'UserController@showProfile'));

/**
 * 获取用户所有的联系人route
 */
Route::get('/getContactPeople', array('before' => 'auth', 'uses' => 'ContactPeopleController@getContactPeople'));

/**
 * 用户添加一个的联系人route
 */
Route::post('/addContactPeople', array('before' => 'auth', 'uses' => 'ContactPeopleController@addContactPeople'));

/**
 * 用户删除一个的联系人route
 */
Route::post('/deleteContactPeople', array('before' => 'auth', 'uses' => 'ContactPeopleController@deleteContactPeople'));

/**
 * 用户更新一个的联系人route
 */
Route::post('/updateContactPeople', array('before' => 'auth', 'uses' => 'ContactPeopleController@updateContactPeople'));

/**
 * 用户预约route
 */
Route::get('/doReserve', array('before' => 'auth|reservationNumberLimited', 'uses' => 'UserController@doReserve'));


/**
 * 用户确认所有预约信息后确认预约route
 */
Route::get('/confirmReserve', array('before' => 'auth|reservationNumberLimited', 'uses' => 'UserController@confirmReserve'));

/*---------------------------------------------------------
 * 管理员相关route
 * --------------------------------------------------------
 */


/**
 * 获取管理员登录页面route
 */
Route::get('/admin/login', function()
{
    return View::make('admin/login');
});


/**
 * 处理管理员登录请求route
 */
Route::post("/admin/login", 'AdminController@login');



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
Route::get('/validateSMS/{phoneNumber}', function($phoneNumber) {
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
        $response = array('sendStatus' => '1');
    } else {

        $response = array(
            'sendStatus' => '0',
            'message' => $smsValidator->getMessages()
        );
    }    return Response::json($response);
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
 * 按“医院地区”和“等级”返回医院信息
 */
Route::get('/hospital_district/{city}/{level}','HospitalController@getHospitalByCityAndLevel');

/**
 * 按“医院地区”返回医院名称
 */
Route::get('/hospital_name/{city}','HospitalController@getHospitalNameByCity');

/**
 * 按“医院名称”返回医院科室
 */
Route::get('/hospital_department/{hospitalName}','HospitalController@getDepartmentByHospitalName');

/**
 * 按“科室名称”返回医院
 */
Route::get('/department/{departmentName}','DepartmentController@getHospitalByDepartment');

/**
 * “热门医院”的获取
 */
Route::get('/hot_hospital','HospitalController@getHotHospital');
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

/**
 * 通过三级行政地区的id查询该地区所在城市
 */
Route::post('/cityName', function()
{
    return \Cheetah\Services\Districts\District::getCityName(Input::get('district_id'));
});

/**
 * 获取一级科室信息, 根据一级科室id获取二级科室信息, 根据二级科室id获取其详细信息
 */
Route::get('/departmentLevelOne', 'DepartmentController@getDepartmentLevelOne');
Route::get('/departmentLevelTwo/{department_id}', 'DepartmentController@getDepartmentLevelTwo');
Route::get('/departmentLevelTwoDetail/{department_id}', 'DepartmentController@getDepartmentLevelTwoDetail');

/**
 * 获取特定科室的号源信息, 获取特定科室的通用信息(开始挂号时间, 结束挂号时间等)
 */
Route::post('/reservationNumberInfo', 'DepartmentController@getReservationNumberInfo');
Route::post('/departmentInfo', 'DepartmentController@getDepartmentInfo');

/**
 * 通过医院名称或科室名称搜索, 返回医院信息数组
 */
Route::post('/search', 'SearchController@search');

/**
 * 通过二级科室类别id和地区名获取相关医院信息
 */
Route::post('/hospitalInfo', 'DepartmentController@getHospitalInfo');

