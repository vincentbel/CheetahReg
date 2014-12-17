<?php
/**
 * Created by PhpStorm.
 * User: VincentBel
 * Date: 2014/12/2
 * Time: 10:49
 */

class UserController extends BaseController
{

    protected $user;

    function __construct(User $user)
    {
        $this->user = $user;
    }


    /**
     * 注册一个新用户
     */
    public function register()
    {

        $SMSCode = Input::get('SMSCode');


        $validator = New \Cheetah\Services\Validation\SMSValidator();

        // 验证手机验证码是否正确
        if (empty($SMSCode) || ! $validator -> verifySMSCode($SMSCode)) {
            return Response::json(array(
                'success' => 0,
                'message' => '手机验证码错误',
            ));
        }

        $input = array(
            'gender' => Input::get('gender'),
            'real_name' => Input::get('realName'),
            'ID_card_number' => Input::get('IdCardNumber'),
            'password' => Hash::make(Input::get('password')),
            'mobile_number' => Input::get('phoneNumber')
        );

        $this->user->fill($input);

        // 如果验证不通过，返回错误信息
        if ( ! $this->user->isValid()) {
            return Response::json(array(
                'success' => 0,
                'message' => $this->user->error,
            ));
        }

        // 验证成功，保存用户到数据库中
        $this->user->save();

        return Response::json(array(
            'success' => 1,
            'message' => "注册成功",
        ));

    }


    /**
     * 登录
     */
    public function login()
    {
        // 登录的身份证号或者手机号
        $mixPassport = Input::get('mixPassport');

        // 登录密码
        $password = Input::get('password');


        // 返回的信息
        $response = array();

        if (strlen($mixPassport) == '11') {
            // 使用手机号登录

            if (Auth::attempt(array('mobile_number' => $mixPassport, 'password' => $password), true)) {
                $response['success'] = 1;
                $response['message'] = '成功通过手机号登录';
            } else {
                // 通过手机号登录失败
                $response['success'] = 0;
                $response['message'] = '手机号或者密码错误';
            }

        } elseif (\Cheetah\Services\Validation\IdCardAndNameValidator::isIdCardCorrect($mixPassport)) {
            // 使用身份证号登录

            if (Auth::attempt(array('ID_Card_Number' => $mixPassport, 'password' => $password), true)) {
                $response['success'] = 1;
                $response['message'] = '成功通过身份证号登录';
            } else {
                $response['success'] = 0;
                $response['message'] = '身份证号或者密码错误';
            }
        } else {
            // 输入不符合规范

            $response['success'] = 0;
            $response['message'] = '输入有误';
        }

        return Response::json($response);
    }

    public function showProfile()
    {
        $this->user->reservations;

    }


    protected $contactPeople;

    /**
     * 添加一个新的联系人
     */
    public function addContactPeople()
    {
        $input = array(
            'real_name' => Input::get('realName'),
            'gender' => Input::get('gender'),
            'ID_card_number' => Input::get('IdCardNumber'),
            'user_id' => Auth::user()->user_id,
        );

        $contactPeople = new ContactPeople;
        $contactPeople->fill($input);

        // 验证输入是否合法
        if (! $contactPeople->isValid()) {
            return Response::json(array(
                'success' => 0,
                'message' => $this->user->error,
            ));
        }

        $contactPeople->save();

        return Response::json(array(
            'success' => 1,
            'message' => "添加联系人成功",
        ));
    }

    /**
     * 获取用户的所有联系人
     *
     * @return json 当没有联系人时，返回一个空数组
     */
    public function getContactPeople()
    {
        $this->user = Auth::user();

        $contactPeoples = $this->user->contact_people;

        $responses = array();
        foreach ($contactPeoples as $key => $contactPeople) {
            $responses[$key] = array(
                'gender' => $contactPeople->gender,
                'realName' => $contactPeople->real_name,
                'IdCardNumber' => $contactPeople->ID_card_number
            );
        }
        return Response::json($responses);
    }

    /**
     * 用户预约
     */
    public function doReserve()
    {
        $reservationNumberInfoId = Input::get('reservationNumberInfoId');

        // 如果输入的号源id为空或者不是数字，返回错误信息
        if (empty($reservationNumberInfoId) || ! is_numeric($reservationNumberInfoId)) {
            return Response::json(array(
                'success' => 0,
                'message' => '输入有误'
            ));
        }

        // 根据id获取号源信息
        $reservationNumberInfo = ReservationNumberInfo::find($reservationNumberInfoId);

        // 如果根据id找不到号源信息，则返回错误信息
        if ($reservationNumberInfo == null) {
            return Response::json(array(
                'success' => 0,
                'message' => '请求错误，所请求的号源不存在'
            ));
        }

        // 如果剩余数量为0，说明号源已经被预约完，返回提示信息
        if ($reservationNumberInfo->remain_number <= 0) {
            return Response::json(array(
                'success' => 0,
                'message' => '您预约的号源已经全部被预约'
            ));
        }

        // 用户能成功预约，号源剩余数量减一
        $reservationNumberInfo->remain_number--;

        $reservationNumberInfo->save();

        // TODO 返回确认预约所需要的信息

    }
}