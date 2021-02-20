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

Route::get('tags/withCategories', 'TagsController@getBySeccion');
Route::resource('tags', 'TagsController');
Route::get('tags/byCategory/{id}','TagsController@tagsByCategory');

Route::apiResource('profile','ProfileController');
Route::apiResource('usermeta','UsersMetasController');

Route::post('post/findPostsPopular','PostEventController@findPostsPopular');
Route::get('post/find/{id}','PostEventController@show')->name('post.show');
Route::post('post','PostEventController@store')->name('post.store');
Route::post('post/setPopular','PostEventController@setPostPopular');
Route::post('post/setState','PostEventController@switchStatePost');

Route::get('post/populars','PostEventController@popularPost');


Route::get('categories','CategoriesController@index');
Route::post('categories/saveImgPresentation','CategoriesController@changeImgPresentation');
Route::post('categories','CategoriesController@store')->name('tags.store');

//experiementando si las request post o get necesitan toke 
Route::get('routeget/{id}','ArtistaController@requestget');
Route::post('routepost/{id}','ArtistaController@requestpost');





