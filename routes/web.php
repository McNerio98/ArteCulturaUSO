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
Route::get('/events','WebsiteController@events')->name('events');
//Route::get('/page1','WebsiteController@artitas')->name("artistas");
//Route::get('/page2','WebsiteController@promotores')->name("promotores");
//Route::get('/page3','WebsiteController@escuelas')->name("escuelas");
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
#Muestra la vista para editar un post 
Route::get('/perfil/{idUser}/post/edit/{idPost}','ProfileController@editElement')->name('profile.edit.item')->middleware('auth');



//Routes para el administrador 
Route::get('/admin/home','DashboardController@index')->name('dashboard');
Route::get('/admin/content','DashboardController@content')->name('content');
Route::get('/admin/search','DashboardController@search')->name('admin.search'); //dado que hay otra para el cliente 
Route::get('/admin/memories','DashboardController@memories')->name('memories');
Route::get('/admin/populars','DashboardController@populars')->name('populars');
Route::get('/admin/users','DashboardController@users')->name('users');
Route::get('/admin/categories','DashboardController@rubros')->name('rubros'); //categories and tags 
Route::get('/admin/resources','DashboardController@resources')->name('resources');
Route::get('/admin/roles','DashboardController@roles')->name('roles');
Route::get('/admin/users/config/{id}','DashboardController@infoUser')->name('user.info');
Route::get('/admin/post/edit/{id}','DashboardController@editElement')->name('admin.edit.item');

//Routes para busquedas 
Route::get('/search','SearchController@index')->name('search');



//Middleware addroles filtra que el usuario sea un administrador y no un invitado, si es invitado lo redirecciona 
//Routes para petticiones ajax
Route::get('/approval','PostEventController@approval')->name('items.approval')->middleware('auth','adroles');
Route::get('/users/dataConfig/{id}','UsersController@configUserData')->name("user.dataconf")->middleware('auth','adroles');
Route::put('/user/updateConfig/{id}','UsersController@updateConfigUser')->name("user.updateconf")->middleware('auth','adroles');

#Obtiene los usuarios dentro de la plataforma, con filtros para todos los usuarios, solicitudes, habilitados, no activos 
Route::get('users','UsersController@index')->name('users.fetch')->middleware('auth','adroles');
# Guarda la fotografia de presentacion de la categoria que se esta editando 
Route::post('/categories/saveImgPresentation','CategoriesController@changeImgPresentation')->middleware('auth','adroles');
# Almacena una nueva categoria 
Route::post('categories','CategoriesController@store')->name('cat.store')->middleware('auth','adroles');
#Actualiza una etiqueta 
Route::put('tags/{id}','TagsController@update')->name("tag.update")->middleware('auth','adroles');
Route::post('tags','TagsController@store')->name('tags.store')->middleware('auth','adroles');
#Obtiene todas las etiquetas mediante el paso de una categoria , solo es necesario que este logeado sea rol o podria ser un invitado 
Route::get('tags/byCategory/{id}','TagsController@tagsByCategory')->name('tag.select')->middleware('auth');

#Obtiene los notificadores, usuario, eventos, events para los paneles notificaciones en la pagina principal del admin 
#Esta ruta ya tiene los middleware adroles desde el constructor 
Route::get('/notifiers','DashboardController@notifiers')->name("notifiers");
#Almacena un elemento, publicacion o evento con sus medios digitales 
Route::post('postevent','PostEventController@store')->name('postevent.store')->middleware('auth');
#Actualiza un elemento, publicacion o evento con sus medios digitales 
Route::put('postevent/{id}','PostEventController@update')->name('postevent.update')->middleware('auth');

//Middleware que necesitan que el usuario este logeado 
#Guardar nueva etiqueta dentro del perfil 
Route::put('/profile/tags/{id}','ProfileController@updateTags')->middleware('auth');
#Eliminar etiqueta desde perfil 
Route::delete('/profile/deltag/{idu}/{idtg}','ProfileController@deleteTag')->middleware('auth');
#Guardar informacion del usuario, informacion como ['user_email','user_phone','user_other_name','user_nickname'];
#Todas las rutas deben estar sin el separador(/)
Route::post('users','UsersController@store')->middleware('auth');
#Guarda cualquier meta dato del usuario
Route::post('usersmeta','UsersMetasController@store')->middleware('auth');


//RUTAS AJAX QUE NO NECESITAN PROTECCION
Route::get('/profile/{id}','ProfileController@show');
#Muestra la lista de elementos post y eventos del usuario parado como id 
Route::get('/postsevents/{id}','ProfileController@elements');





Route::get('res/send', 'RequestAcountController@index');
