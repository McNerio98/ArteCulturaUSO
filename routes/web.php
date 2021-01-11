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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/artista/{id?}', "ArtistaController@show");











Route::get('/waiting','Auth\LoginController@waiting')->name('waiting');



Route::get('/postpage',function(){
    return view('pagePost');
});


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




