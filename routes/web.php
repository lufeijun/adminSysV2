<?php

use App\System\Menu;
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
Route::get('/fuck', function () {
    Menu::checkActionGranted('一级,二级,功能一',true);

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


Route::get('no-privilege', function () {
    return view('errors.privilege');
});
Route::get('not-found', function () {
    return view('errors.notFound');
});
