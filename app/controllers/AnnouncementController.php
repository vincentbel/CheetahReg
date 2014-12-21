<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2014/12/14
 * Time: 18:22
 */

class AnnouncementController  extends BaseController{
    protected $announcement;

    /**
     * @param Announcement $announcement
     */
    function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * 发布公告
     */
    public function addAnnouncement()
    {
        $input = array(
            'admin_id' =>  \Session::get('adminId'),
            'subject' => Input::get('subject'),
            'message' => Input::get('message'),
            'author' =>Input::get('author')
        );
        $this->announcement->fill($input);

        if($this->announcement->isValid())
        {
            $this->announcement->save();
            return Response::json(array(
                'success' => 1,
                'message' => "添加成功",
            ));
        }
        //添加不成功，返回错误信息
        else
        {
            return Response::json(array(
                'success' => 0,
                'message' => $this->announcement->error,
            ));
        }
    }

    public function deleteAnnoucement()
    {
        $this->announcement->fill(array(
            'announcement_id' =>  \Session::get('announcementId')
        ));
        $this->announcement->delete();
    }
} 