<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2014/12/15
 * Time: 15:05
 */

return array(

    'title' => 'Announcements',
    'single' => 'Announcements',
    'model' => 'Announcement',

    'columns' => array(
        'subject' => array(
            'title' => 'subject',
        ),
        'message' => array(
            'title' => 'message',
        ),
        'author' => array(
            'title' => 'author',
        ),
        'post_time' => array(
            'title' => 'post_time',
        ),
        'announcement_id' => array(
            'tittle' => 'announcement_id'
        ),
    ),

    'edit_fields' => array(
        'subject' => array(
            'title' => 'subject',
            'type' => 'text',
        ),
        'message' => array(
            'title' => 'message',
            'type'  =>  'textarea',
        ),
        'author' => array(
            'title' => 'author',
            'type' => 'text',
        ),
        'post_time' => array(
            'title' => 'post_time',
            'type' => 'date',
        ),
        'announcement_id' => array(
            'title' => 'announcement_id',
            'type' => 'key',
        ),
    ),

);