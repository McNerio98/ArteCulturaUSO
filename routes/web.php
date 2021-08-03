<?php

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

//Router Pagina principal 
Route::get('/','WebsiteController@welcome')->name("inicio");
Route::get('/page1','WebsiteController@artitas')->name("artistas");
Route::get('/page2','WebsiteController@promotores')->name("promotores");
Route::get('/page3','WebsiteController@escuelas')->name("escuelas");
Route::get('/page4','WebsiteController@recursos')->name("recursos");
Route::get('/page5','WebsiteController@biografias')->name("biografias");
Route::get('/page6','WebsiteController@homenajes')->name("homenajes");
Route::get('/page7','WebsiteController@acercade')->name("acercade");

Route::get('/request/status/{name}','WebsiteController@accountRequest')->name("request_status");



Route::get('/waiting','Auth\LoginController@waiting')->name('waiting');



//McNerio Routes 
Route::get('/login','Auth\LoginController@showLoginForm')->middleware('guest');
Route::post('/login','Auth\LoginController@login')->name('login');
Route::post('/logout','Auth\LoginController@logout')->name('logout');


Route::get('/perfil/{id}','ProfileController@index')->name('profile.show');




//Routes para el administrador 
Route::get('/admin/home','DashboardController@index')->name('dashboard');
Route::get('/admin/content','DashboardController@content')->name('content');
Route::get('/admin/memories','DashboardController@memories')->name('memories');
Route::get('/admin/populars','DashboardController@populars')->name('populars');
Route::get('/admin/users','DashboardController@users')->name('users');
Route::get('/admin/categories','DashboardController@rubros')->name('rubros'); //categories and tags 
Route::get('/admin/resources','DashboardController@resources')->name('resources');
Route::get('/admin/roles','DashboardController@roles')->name('roles');
Route::get('/admin/users/config/{id}','DashboardController@infoUser')->name('user.info');

//Routes para busquedas 
Route::get('/search','SearchController@index')->name('search');


//Route::post('rolesdata/{id}','RequestAcountController@indexRoles')->middleware('auth');



//Middleware addroles filtra que el usuario sea un administrador y no un invitado, si es invitado lo redirecciona 
//Routes para petticiones ajax
Route::get('/approval','PostEventController@approval')->name('items.approval')->middleware('auth','adroles');
Route::get('/users/dataConfig/{id}','UsersController@configUserData')->name("user.dataconf")->middleware('auth','adroles');
Route::put('/user/updateConfig/{id}','UsersController@updateConfigUser')->name("user.updateconf")->middleware('auth','adroles');
Route::get('/notifiers','DashboardController@notifiers')->name("notifiers"); //ya tiene los middleware el controller 

Route::post('postevent','PostEventController@store')->name('postevent.store')->middleware('auth');


//rutas que no necesitan proteccion 
Route::get('/profile/{id}','ProfileController@show');
Route::get('/postsevents/{id}','ProfileController@elements');





Route::get('res/send', 'RequestAcountController@index');
