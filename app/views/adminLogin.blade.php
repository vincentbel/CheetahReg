<?php
/**
 * Author: VincentBel
 * Date: 2014/12/17
 * Time: 11:46
 */
?><!doctype html>
<html>
<head>
	<title>管理员登录</title>
    <style>
        .form-signin
        {
            max-width: 330px;
            padding: 15px;
            margin: 0 auto;
            padding-top: 5px;
        }
        .form-signin .form-signin-heading, .form-signin .checkbox
        {
            margin-bottom: 10px;
        }
        .form-signin .checkbox
        {
            font-weight: normal;
        }
        .form-signin .form-control
        {
            position: relative;
            font-size: 16px;
            height: auto;
            padding: 10px;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            width: 100%;
        }
        .form-signin .form-control:focus
        {
            z-index: 2;
        }
        .form-signin input[type="password"]
        {
            margin-bottom: 10px;
        }

        .btn-lg {
            display: block;
            width: 100%;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            height: 36px;
            border: 1px solid #3079ed;
            color: #fff;
            text-shadow: 0 1px rgba(0,0,0,0.1);
            background-color: #4d90fe;
        }

        .header {
            margin: 0 auto;
            margin-top: 76px;
            display: block;
        }

        .logo {
            margin: 0 auto;
            display: block;
            max-width: 360px;
        }
        .logo-text {
            display: block;
            margin: 0 auto;
            color: #777;
            font-size: 16px;
            font-weight: bold;
            left: 83px;
            position: relative;
            top: -28px;
            white-space: nowrap;
            width: 0;
        }

    </style>
</head>
<body>


<div>
    <div class="header">
        <img class="logo" src="http://youone.sinaapp.com/images/logo-admin.png" alt="logo">
        <span class="logo-text">Admin</span>
    </div>
    {{ Form::open(array('action' => 'AdminController@login', 'class'=>'form-signin')) }}
    {{ Form::text('username', $value = null, array('placeholder' => '用户名', 'class'=> 'form-control', 'required' => 'required', 'autofocus' => 'autofocus' )) }}
    {{ Form::password('password', array('placeholder' => '密码', 'class' => 'form-control', 'required' => 'required')) }}
    {{ Form::submit('登录', array('class' => 'btn-lg')) }}
    {{ Form::close() }}
</div>