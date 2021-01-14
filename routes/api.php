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

//Routes para el admin panel de administradores
Route::apiResource('requestaccounts', 'RequestAcountController');
//Route::apiResource('post', 'CreatePostEvent');
Route::apiResource('users', 'UsersController');

Route::get('tags/tagswithseccions', 'TagsController@getBySeccion');
Route::resource('tags', 'TagsController');

Route::apiResource('profile','ProfileController');

Route::post('post/findPostsPopular','PostEventController@findPostsPopular');
Route::get('post/find/{id}','PostEventController@show')->name('post.show');
Route::post('post','PostEventController@store')->name('post.store');
Route::get('post/setPopular/{id}/{stcurrent}','PostEventController@setPostPopular');




