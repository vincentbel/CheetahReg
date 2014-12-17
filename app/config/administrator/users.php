<?php
/**
 * Author: VincentBel
 * Date: 2014/12/14
 * Time: 20:11
 */

return array(

    'title' => 'Users',
    'single' => 'User',
    'model' => 'User',

    'columns' => array(
        'ID_card_number' => array(
            'title' => 'ID Card Number',
        ),
        'real_name' => array(
            'title' => 'Real Name',
        ),
    ),

    'edit_fields' => array(
        'ID_card_number' => array(
            'title' => 'ID Card Number',
            'type' => 'text',
        ),
        'real_name' => array(
            'title' => 'Real Name',
            'type' => 'text',
        ),
        'password' => array(
            'title' => 'Password',
            'type' => 'password',
        ),
    ),
);