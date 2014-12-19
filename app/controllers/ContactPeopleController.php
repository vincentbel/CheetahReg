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
                'gender' => $contactPeople->gender,
                'realName' => $contactPeople->real_name,
                'IdCardNumber' => $contactPeople->ID_card_number,
                'isMyself' => $contactPeople->is_myself
            );
        }
        return Response::json($responses);
    }

}