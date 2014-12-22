<?php
/**
 * Author: VincentBel
 * Date: 2014/12/19
 * Time: 15:30
 */

class ContactPeopleController extends BaseController
{

    protected $contactPeople;

    function __construct(ContactPeople $contactPeople)
    {
        $this->contactPeople = $contactPeople;
    }

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

        $this->contactPeople->fill($input);

        // 验证输入是否合法
        if (! $this->contactPeople->isValid()) {
            return Response::json(array(
                'success' => 0,
                'message' => $this->contactPeople->error,
            ));
        }

        $this->contactPeople->save();

        return Response::json(array(
            'success' => 1,
            'contactPeopleId' => $this->contactPeople->contact_people_id,
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

        $contactPeoples = Auth::user()->contact_people;

        $responses = array();
        foreach ($contactPeoples as $key => $contactPeople) {
            $responses[$key] = array(
                'contactPeopleId' => $contactPeople->contact_people_id,
                'gender' => $contactPeople->gender,
                'realName' => $contactPeople->real_name,
                'IdCardNumber' => $contactPeople->ID_card_number,
                'isMyself' => $contactPeople->is_myself
            );
        }
        return Response::json($responses);
    }

    /**
     * 根据联系人id删除一个联系人
     */
    public function deleteContactPeople()
    {
        $contactPeopleId = Input::get('contactPeopleId');
        $contactPeople = ContactPeople::find($contactPeopleId);

        // 如果根据id找不到联系人，则返回错误信息
        if ($contactPeople == null || $contactPeople->user_id !== Auth::user()->user_id) {
            return Response::json(array(
                'success' => 0,
                'message' => '请求错误，联系人不存在'
            ));
        }

        // 如果根据id找到的联系人是用户自己，则返回错误信息
        if ($this->contactPeople->is_myself != 1) {
            return Response::json(array(
                'success' => 0,
                'message' => '请求错误'
            ));
        }

        $contactPeople->delete();

        return Response::json(array(
            'success' => 1,
            'message' => "成功删除联系人",
        ));
    }


    /**
     * 根据联系人id更新一个联系人
     */
    public function updateContactPeople()
    {
        $contactPeopleId = Input::get('contactPeopleId');
        $gender = Input::get('gender');

        if ( ! is_numeric($gender)) {
            return Response::json(array(
                'success' => 0,
                'message' => '输入有误'
            ));
        }

        $this->contactPeople = ContactPeople::find($contactPeopleId);

        // 如果根据id找不到的联系人不属于当前用户，则返回错误信息
        if ($this->contactPeople == null || $this->contactPeople->user_id !== Auth::user()->user_id) {
            return Response::json(array(
                'success' => 0,
                'message' => '请求错误，联系人不存在'
            ));
        }

        // 如果根据id找到的联系人是用户自己，则返回错误信息
        if ($this->contactPeople->is_myself == 1) {
            return Response::json(array(
                'success' => 0,
                'message' => '请求错误'
            ));
        }

        $this->contactPeople->save();

        return Response::json(array(
            'success' => 1,
            'contactPeopleId' => $this->contactPeople->contact_people_id,
            'message' => "更新联系人成功",
        ));
    }
}