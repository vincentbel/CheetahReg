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
        $this->user = Auth::user();

        $response = array();

        $response['reservations'] = $this->user->reservationNumbers();

        return Response::json($response);

    }

    /**
     * 用户预约
     */
    public function doReserve()
    {
        $this->user = Auth::user();
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


        // 用户如果预约已经预约过的科室，返回错误信息
        $reservedDepartment = $this->user->reservationNumbers()->fetch('department_id')->toArray();


        if (in_array($reservationNumberInfo->department_id, $reservedDepartment)) {
            return Response::json(array(
                'success' => 0,
                'message' => '您已经预约过此科室的医生，系统不允许同时预约的两个同一科室的医生'
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

        // 将预约信息加入到预约表 reservation 中，默认为联系人中的自己预约
        $userInContactPeople = $this->user->MySelfInContactPeople();
        $userInContactPeople->reservationNumbers()->attach($reservationNumberInfoId,
            array(
                'reservation_status' => 1,
                'sequence_number' => ($reservationNumberInfo->total_number - $reservationNumberInfo->remain_number)
            ));

        // create event
        $this->createEvent($reservationNumberInfoId, $userInContactPeople->contact_people_id);

        // 返回成功预约信息
        return Response::json(array(
            'success' => 1,
            'message' => '您可以在10分钟内完成预约过程'
        ));

    }


    public function confirmReserve()
    {
        $SMSCode = Input::get('SMSCode');
        $reservationNumberInfoId = Input::get('reservationNumberInfoId');
        $contactPeopleId = Input::get('contactPeopleId');

        $validator = New \Cheetah\Services\Validation\SMSValidator();

        // 验证手机验证码是否正确
        if (empty($SMSCode) || ! $validator -> verifySMSCode($SMSCode)) {
            return Response::json(array(
                'success' => 0,
                'message' => '手机验证码错误',
            ));
        }

        $reservationStatus = DB::table('reservation')->where('reservation_number_info_id', '=', $reservationNumberInfoId)
                             ->where('contact_people_id', '=', $contactPeopleId)->first()->pluck('reservation_status');

        if ($reservationStatus)
        {
            DB::table('reservation')->where('reservation_number_info_id', '=', $reservationNumberInfoId)
                ->update(array('reservation_status' => '2', 'contact_people_id' => $contactPeopleId));
            $this->dropEvent($reservationNumberInfoId, $contactPeopleId);
        }
    }

    /**
     * @param $reservationNumberInfoId
     * @param $contactPeopleId
     */
    private function createEvent($reservationNumberInfoId, $contactPeopleId)
    {
        $eventName = $reservationNumberInfoId."_".$contactPeopleId;
        $createEvent = "CREATE EVENT ".$eventName." ON SCHEDULE AT CURRENT_TIMESTAMP
                 + INTERVAL 10 MINUTE DO BEGIN DELETE FROM vincentz_HRRS.reservation WHERE reservation_number_info_id =
                 $reservationNumberInfoId AND contact_people_id = $contactPeopleId; UPDATE vincentz_HRRS.reservation_number_info
                 SET remain_number = remain_number + 1 WHERE reservation_number_info_id = $reservationNumberInfoId; END";

        $reservationStatus = DB::table('reservation')->where('reservation_number_info_id', '=', $reservationNumberInfoId)
                             ->where('contact_people_id', '=', $contactPeopleId)->pluck('reservation_status');

        if ($reservationStatus)
        {
            DB::unprepared($createEvent);
        }

    }

    /**
     * @param $reservationNumberInfoId
     * @param $contactPeopleId
     */
    private function dropEvent($reservationNumberInfoId, $contactPeopleId)
    {
        $eventName = $reservationNumberInfoId."_".$contactPeopleId;
        $dropEvent = "DROP EVENT IF EXISTS ".$eventName;

        DB::unprepared($dropEvent);

    }
}