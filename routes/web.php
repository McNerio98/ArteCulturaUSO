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



Route::get('/waiting','Auth\LoginController@waiting')->name('waiting');



//McNerio Routes 
Route::get('/login','Auth\LoginController@showLoginForm')->middleware('guest');
Route::post('/login','Auth\LoginController@login')->name('login');
Route::post('/logout','Auth\LoginController@logout')->name('logout');

//Routes para navegacion con o sin logeado
/*Route::get('/inicio','WebsiteController@index')->name('welcome');
Route::get('/events','WebsiteController@events')->name('events');
Route::get('/artistas','WebsiteController@artistas')->name('artistas');
Route::get('/promotores','WebsiteController@promotores')->name('promotores');
Route::get('/escuelas','WebsiteController@escuelas')->name('escuelas');
Route::get('/recursos','WebsiteController@acercade')->name('acercade');*/
Route::get('/perfil','WebsiteController@profile')->name('profile');




//Routes para el administrador 
Route::get('/admin/home','DashboardController@index')->name('dashboard');
Route::get('/admin/users','DashboardController@users')->name('users');
Route::get('/admin/tags','DashboardController@tags')->name('tags');
Route::get('/admin/page3','DashboardController@rubros')->name('rubros');




