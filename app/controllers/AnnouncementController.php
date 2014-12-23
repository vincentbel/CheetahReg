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
     * 返回数据库中所有的公告信息
     */
    public function getAllAnnouncements()
    {
        // 返回的信息
        $response = array();
        $results = Announcement::all();
        $response['results'] = $results;
        return  Response::json($response);
    }
} 