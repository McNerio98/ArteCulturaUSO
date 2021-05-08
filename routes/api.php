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

//Router de pruebas
//para validaciones
Route::post('storeTestValid','RequestAcountController@storeWithValidate');
Route::post('storeTestValidJson','RequestAcountController@storeWithValidateJson');




//Routes para el admin panel de administradores

Route::post('requestaccounts','UsersController@requestAccount');
//Route::apiResource('post', 'CreatePostEvent');
Route::apiResource('users', 'UsersController');
Route::get('users/dataConfig/{id}','UsersController@configUserData');
Route::put('user/updateConfig/{id}','UsersController@updateConfigUser');
Route::get('user/existEmail/{id}/{mail}','UsersController@validateEmail');
Route::get('user/existUsername/{id}/{username}','UsersController@validateUsername');


Route::get('tags/withCategories', 'TagsController@getBySeccion');
Route::resource('tags', 'TagsController');
Route::get('tags/byCategory/{id}','TagsController@tagsByCategory');

Route::apiResource('profile','ProfileController');
Route::put('profile/tags/{id}','ProfileController@updateTags');
Route::delete('profile/deltag/{idu}/{idtg}','ProfileController@deleteTag');

//aqui para agregar todas las metas 
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


//Rutas para busquedas 
//por categoria 
Route::get('search/byCategory/{id}','SearchController@byCategory');

//experiementando si las request post o get necesitan toke 
Route::get('routeget/{id}','ArtistaController@requestget');
Route::post('routepost/{id}','ArtistaController@requestpost');





