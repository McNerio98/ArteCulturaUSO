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



//Depurar estos 
Route::apiResource('users', 'UsersController');
Route::apiResource('profile','ProfileController');

//Route::get('user/{id}','ProfileController@show')->


#Obtiene las categorias con sus etiquetas segmentadas 
Route::get('tags/withCategories', 'TagsController@getBySeccion');






//ESTOS SE QUEDAN AQUI, PUEDEN SER UTILIZADOS PARA UNA API FUTURA
#Obtiene la informacion de un elemento/ evento/publicacion 
Route::get('post/{id}','PostEventController@show')->name('post.show');
#Obtiene los eventos dentro de los tres meses siguientes 
Route::get('posts/events','PostEventController@eventsTable')->name('table.events');

# Obtiene la lista de elementos destacados, independiente si esta o no aprovados 
Route::get('posts/populars','PostEventController@popularItems')->name("posts.populars");

# Obtiene todas las etiquetas, respuesta en JSON 
Route::get('tags','TagsController@index')->name('tags.index');
# Obtiene todas las categorias, respuesta en JSON 
Route::get('categories','CategoriesController@index');

# falta terminarla, pero inicialmente realiza las busquedas a traves de ajax 
Route::get('exeSearch','SearchController@execSearch')->name("search.exec");

#Obtiene la informacion basica y las fotografias de perfiles de un usuario 
Route::get('profile/gnInfo/{id}','ProfileController@summaryInfo')->name('profile.general.info');
# Ontiene el modelo del usuario y los metadatos 
Route::get('profile/aboutUser/{id}','ProfileController@aboutInfo')->name('profile.about.info');
#Verifica si ya existe un usuario con el numero de telefono especifico
Route::get('user/existTelephone/{id}/{target}','UsersController@validateTelephone');
#Verifica si ya existe un usuario con el email  especifico
Route::get('user/existEmail/{id}/{mail}','UsersController@validateEmail');
#Verifica si ya existe un usuario con el nombre de usuario especifico
Route::get('user/existUsername/{id}/{username}','UsersController@validateUsername');
#Envia datos para su almacenaje como nueva solicitud de cuenta 
Route::post('requestaccounts','UsersController@requestAccount');
# ===========



Route::post('post/setPopular','PostEventController@setPostPopular');
Route::post('post/setState','PostEventController@switchStatePost');

Route::get('post/populars','PostEventController@popularPost');



//Rutas para busquedas 
//por categoria 
Route::get('search/byCategory/{id}','SearchController@byCategory');

Route::get('roles','RolesController@index')->name("roles.index");
Route::get('roles/{id}','RolesController@show')->name("roles.show");
Route::put('roles/{id}','RolesController@update')->name("roles.update");


