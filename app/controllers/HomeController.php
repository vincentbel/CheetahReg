<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome()
	{
		return View::make('hello');
	}

    /**
     * 返回一级行政区的地区id和地区名称数组
     *
     * @return mixed
     */
    public function postDistrictOne()
    {
        return District::levelOne()->get();

    }

    /**
     * 通过一级行政区的id查询并返回二级行政区的地区id和地区名称数组
     *
     * @return mixed
     */
    public function postDistrictTwo()
    {
        $district_id = Input::get('district_id');
        return District::levelTwo($district_id)->get();
    }

    /**
     * 通过二级行政区的id查询并返回三级行政地区的地区id和地区名称数组
     *
     * @return mixed
     */
    public function postDistrictThree()
    {
        $district_id = Input::get('district_id');
        return District::levelThree($district_id)->get();
    }
}
