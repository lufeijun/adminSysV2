<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('fuck', function () {
//    echo App\System\Admin::getLoginedMessage('id');


    echo Hash::make( '111222333' );



    var_dump( password_verify('111222333' , '$2y$10$VWRqpM/39NVFW1cKXkhpyuoitbvONcZyXSri9dAfOh2PdW6IkbViK') );

});
Route::get('/', function () {
  return redirect('admin');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/admin', 'HomeController@index')->middleware('auth')->name('home');

Route::middleware(['auth'])->namespace("Admin")->prefix("admin")->group(function () {

    Route::group(['prefix'=>'privilege'],function(){
        Route::get('member/list', 'PrivilegeController@memberList');
        Route::get('role/list', 'PrivilegeController@roleList');
        Route::get('role/permit/show/{id}', 'PrivilegeController@permit');

    });



});


// graphql
Route::any('graphql', 'GraphQLController@fire');
