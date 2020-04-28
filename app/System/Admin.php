<?php
namespace App\System;


/**
 *
 */
class Admin
{

    /**
    * 获取当前登录信息
    */
    public static function getLoginedMessage($field , $default='')
    {

        // 如果没有 session ，直接后台请求
        if ( ! session()->has('administrator') ) {
           Menu::initSession();
        }

        if ( session()->has('administrator') && isset(session('administrator')[$field]) ) {
            return  session('administrator')[$field];
        } else {
            return $default;
        }
    }

    /**
    * 依据不同角色，获取不同的首页
    */
    public static function getHomeUrlByRole()
    {
        $url = url('admin');
        return $url;
    }







}



