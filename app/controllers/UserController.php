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

        // 注册成功后，自动登录用户
        if (Auth::attempt(array('mobile_number' => Input::get('phoneNumber'), 'password' => Input::get('password')), true)) {
            // 成功自动登录
        }

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
        return Response::json($this->user);
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
        $reservedDepartment = $this->user->onReserveNumbers()->fetch('department_id')->toArray();


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

        // hack 根据 contact_people_id,reservation_number_info_id,reservation_status 取得 reservation_id
        $reservationId = DB::table('reservation')
            ->where('contact_people_id', '=', $userInContactPeople->contact_people_id)
            ->where('reservation_number_info_id', '=', $reservationNumberInfoId)
            ->where('reservation_status', '=', 1)
            ->pluck('reservation_id');

        // create event
        $this->createEvent($reservationId, $reservationNumberInfoId);
        // 返回成功预约信息
        return Response::json(array(
            'success' => 1,
            'phoneNumber' => $this->user->mobile_number,
            'reservationId' => $reservationId,
            'message' => '您可以在10分钟内完成预约过程'
        ));

    }


    public function confirmReserve()
    {
        $SMSCode = Input::get('SMScode');
        $contactPeopleId = Input::get('contactPeopleId');
        $reservationId = Input::get('reservationId');

        $validator = New \Cheetah\Services\Validation\SMSValidator();

        // 验证手机验证码是否正确
        if (empty($SMSCode) || ! $validator -> verifySMSCode($SMSCode)) {
            return Response::json(array(
                'success' => 0,
                'message' => '手机验证码错误',
            ));
        }

        $reservationStatus = DB::table('reservation')->where('reservation_id', '=', $reservationId)
                             ->pluck('reservation_status');

        if ($reservationStatus == 1)
        {
            DB::table('reservation')->where('reservation_id', '=', $reservationId)
                ->update(array('reservation_status' => '2', 'contact_people_id' => $contactPeopleId));
            $this->dropEvent($reservationId);
        }

        $response = $this->returnReservationInfo($reservationId);

        // 预约完成后发送短信提醒
        $mobileNumber = $response['mobile_number'];
        $message = '您已成功为'.$response['real_name'].'预约了'.$response['hospital_name'].$response['department_name'].
            '的医师,\n您的就诊日期为'.$response['date'].'时间大约是'.$response['time'].',挂号费为'.$response['reservation_fee'].'请及时取号。';

        if ( ! $validator->sendSMS($mobileNumber, $message)) {
            // 当前只能使用「互亿无线」系统的模板，不能自定义模板
        }
        return $response;
    }

    /**
     * @param $reservationId
     * @param $reservationNumberInfoId
     */
    private function createEvent($reservationId, $reservationNumberInfoId)
    {
        $this->dropEvent($reservationId);
        $eventName = "Event"."_".$reservationId;
        $createEvent = "CREATE EVENT ".$eventName." ON SCHEDULE AT CURRENT_TIMESTAMP
                        + INTERVAL 10 MINUTE DO BEGIN DELETE FROM vincentz_HRRS.reservation WHERE reservation_id = $reservationId;
                        UPDATE vincentz_HRRS.reservation_number_info SET remain_number = remain_number + 1
                        WHERE reservation_number_info_id = $reservationNumberInfoId; END";

        $reservationStatus = DB::table('reservation')->where('reservation_id', '=', $reservationId)
                            ->pluck('reservation_status');

        if ($reservationStatus == 1)
        {
            DB::unprepared($createEvent);
        }

    }

    /**
     * @param $reservationId
     */
    private function dropEvent($reservationId)
    {
        $eventName = "Event"."_".$reservationId;
        $dropEvent = "DROP EVENT IF EXISTS " . $eventName;

        DB::unprepared($dropEvent);
    }

    /**
     * 获取联系人的所有预约记录
     *
     * @param $startDate
     * @param $endDate
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReservations($startDate, $endDate = null)
    {
        $validator = Validator::make(
            array('startDate' => $startDate,     'endDate' => $endDate),
            array('startDate' => 'required|date_format:Y-m-d', 'endDate' =>'date_format:Y-m-d')
        );

        if ($validator->fails()) {
            return Response::make('您好，欢迎加入Cheetah小组！');
        }

        $this->user = Auth::user();

        return Response::json($this->user->reservationNumbers($startDate, $endDate));
    }

    private function returnReservationInfo($reservationId)
    {
        $reservationInfo = Reservation::find($reservationId);
        $reservationNumberInfoId = $reservationInfo->reservation_number_info_id;
        $contactPeopleId = $reservationInfo->contact_people_id;
        $reservationNumberInfo = ReservationNumberInfo::find($reservationNumberInfoId);
        $departmentId = $reservationNumberInfo->department_id;
        $department = Department::find($departmentId);
        $contactPeople = ContactPeople::find($contactPeopleId);

        $response = array();

        $response['hospital_name'] = $department->hospital->hospital_name;
        $response['department_name'] = $department->department_name;
        $response['date'] = $reservationNumberInfo->date;
        $response['created_at'] = $reservationInfo->created_at->toDateTimeString();
        $response['reservation_fee'] = $reservationNumberInfo->reservation_fee;
        $response['real_name'] = $contactPeople->real_name;
        $response['ID_card_number'] = $contactPeople->ID_card_number;
        $response['mobile_number'] = User::where('real_name', '=', $response['real_name'])->pluck('mobile_number');

        // 每个号大约预留30分钟
        $sequenceNumber = $reservationInfo->sequence_number;
        $startTime = $reservationNumberInfo->start_time;
        $seconds = substr($startTime, 0, 2) * 3600 + substr($startTime, 3, 2) * 60 + substr($startTime, 6, 2) + ($sequenceNumber - 1)* 1800;
        $response['time'] = gmdate("H:i", $seconds);

        return $response;
    }

    /**
     * 用户取消预约订单
     */
    public function cancelReserve()
    {
        $reservationId = Input::get('reservationId');

        $reservation = Reservation::find($reservationId);

        // 预约订单信息不存在于系统的记录中,提示其无法取消该订单
        if ($reservation == null) {
            return Response::json(array(
                'success' => 0,
                'message' => '您所取消的订单不存在'
            ));
        }

        // 预约订单已经失效，则通知该会员该订单已经被取消
        if ( ! $reservation->isReservationCancelable()) {
            return Response::json(array(
                'success' => 0,
                'message' => '您的订单'.$reservation->getStatus().'，不能取消',
            ));
        }

        $reservationNumberInfo = ReservationNumberInfo::find($reservation->reservation_number_info_id);
        $department = Department::find($reservationNumberInfo->department_id);

        $cancelDeadline =  strtotime($reservationNumberInfo->date.' '.$department->hospital->registration_cancel_deadline.'-1 days');

        // 验证预约订单是否已经过了退号时间
        if (time() > $cancelDeadline) {
            return Response::json(array(
                'success' => 0,
                'message' => '您的订单已经过了退号时间，请到医院进行退号'
            ));
        }

        // 验证完成，改变预约订单的状态，释放该预约订单的号源
        $reservation->cancelReservation();

        return Response::json(array(
            'success' => 1,
            'message' => '您已成功取消订单'
        ));
    }

}