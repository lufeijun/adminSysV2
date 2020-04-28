<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PrivilegeController extends Controller
{

    // 用户列表
    public function memberList()
    {
        return view('privilege.memberList');
    }

    // 角色列表
    public function roleList()
    {
        return view('privilege.roleList');
    }


    // 角色权限
    public  function  permit($id)
    {
        return view('privilege.permit')->with(['id'=>$id]);
    }




}
