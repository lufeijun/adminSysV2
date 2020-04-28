@extends('layouts.admin')

@section('content')
<div class="my-container">
    <div class="row">
        <div class="col-md-12 margin-bottom">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item><a href="{{ url('')  }}">首页</a></el-breadcrumb-item>
                <el-breadcrumb-item><a href="{{ url('/admin/privilege/role/list')  }}">角色列表</a></el-breadcrumb-item>
                <el-breadcrumb-item>编辑权限</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div class="col-md-12">
            <privilege-permit :id="{{ $id  }}"></privilege-permit>
        </div>
    </div>
</div>
@endsection
