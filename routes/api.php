<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// 上传图片链接
Route::post('upload/image','Api\CommonController@uploadImage');


// 后台接口路由
Route::namespace('Api')->group(function (){

    Route::group(['namespace'=>'Privilege\V1','prefix'=>'privilege/v1'],function(){
        // 用户部分
        Route::group(["prefix"=>'member'],function (){
            Route::post('list','MemberController@list');
            Route::post('add','MemberController@add');
            Route::post('change','MemberController@change');
            Route::post('msg/update/{id}','MemberController@msgUpdate');
            Route::post('password/update/{id}','MemberController@password');
            Route::post('image/update/{id}','MemberController@image');
        });

        // 角色部分
        Route::group(["prefix"=>'role'],function (){
            Route::post('list','RoleController@list');
            Route::post('change','RoleController@change');
            Route::post('permit/get/{id}','RoleController@permitGet');
            Route::post('permit/update/{id}','RoleController@permitUpdate');
        });



    });


});
