@extends('layouts.admin')

@section('content')
<div class="my-container">
    <div class="row">
        <div class="col-md-12 margin-bottom">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item><a href="{{ url('')  }}">首页</a></el-breadcrumb-item>
                <el-breadcrumb-item>角色列表</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div class="col-md-12">
            <privilege-role-list></privilege-role-list>
        </div>
    </div>
</div>
@endsection
