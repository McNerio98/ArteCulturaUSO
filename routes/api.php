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




Route::get('user/existEmail/{id}/{mail}','UsersController@validateEmail');
Route::get('user/existUsername/{id}/{username}','UsersController@validateUsername');
Route::post('user/uploadImgProfile','UsersController@uploadProfileImg');


Route::get('tags/withCategories', 'TagsController@getBySeccion');
Route::get('tags/byCategory/{id}','TagsController@tagsByCategory');

Route::apiResource('profile','ProfileController');
Route::put('profile/tags/{id}','ProfileController@updateTags');
Route::delete('profile/deltag/{idu}/{idtg}','ProfileController@deleteTag');

//aqui para agregar todas las metas 
Route::apiResource('usermeta','UsersMetasController');

Route::post('post/findPostsPopular','PostEventController@findPostsPopular');

//ESTOS SE QUEDAN AQUI, PUEDEN SER UTILIZADOS PARA UNA API FUTURA
Route::get('post/{id}','PostEventController@show')->name('post.show');
Route::get('posts/events','PostEventController@eventsTable')->name('table.events');

# Obtiene todas las etiquetas, respuesta en JSON 
Route::get('tags','TagsController@index')->name('tags.index');
# Obtiene todas las categorias, respuesta en JSON 
Route::get('categories','CategoriesController@index');

# falta terminarla, pero inicialmente realiza las busquedas a traves de ajax 
Route::get('exeSearch','SearchController@execSearch')->name("search.exec");

# ===========



Route::post('post/setPopular','PostEventController@setPostPopular');
Route::post('post/setState','PostEventController@switchStatePost');

Route::get('post/populars','PostEventController@popularPost');



Route::post('categories/saveImgPresentation','CategoriesController@changeImgPresentation');
Route::post('categories','CategoriesController@store')->name('tags.store');


//Rutas para busquedas 
//por categoria 
Route::get('search/byCategory/{id}','SearchController@byCategory');

Route::get('roles','RolesController@index')->name("roles.index");
Route::get('roles/{id}','RolesController@show')->name("roles.show");
Route::put('roles/{id}','RolesController@update')->name("roles.update");


