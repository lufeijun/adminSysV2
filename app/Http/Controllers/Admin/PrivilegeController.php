<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\System\Menu;
use Illuminate\Http\Request;

class PrivilegeController extends Controller
{

    // 用户列表
    public function memberList()
    {
        Menu::checkMenuGranted('权限,成员管理,成员列表','three',true);
        return view('privilege.memberList');
    }

    // 角色列表
    public function roleList()
    {
        Menu::checkMenuGranted('权限,角色管理,角色列表','three',true);
        return view('privilege.roleList');
    }


    // 角色权限
    public  function  permit($id)
    {
        Menu::checkMenuGranted('权限,角色管理,角色列表','three',true);
        return view('privilege.permit')->with(['id'=>$id]);
    }




}
