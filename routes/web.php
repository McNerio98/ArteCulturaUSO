<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

//Verificacion de correo electronico
Route::get('account/verify/{token}','Auth\LoginController@verifyAccount')->name('user.veriry');

//Router Pagina principal 
Route::get('/','WebsiteController@welcome')->name("inicio");
Route::get('/events','WebsiteController@events')->name('events');
Route::get('/cercanos','WebsiteController@nearby')->name('nearby');
//Recursos 
Route::get('/site/recursos','WebsiteController@recursos')->name("recursos");
Route::get('/site/recursos/{id}','RecursosController@show')->name("recursos.show");
#Biosgrafias 
Route::get('/site/biografias','WebsiteController@biografias')->name("biografias");
Route::get('/site/biografias/{id}','MemoriesController@show')->name("recursos.show");

Route::get('/page6','WebsiteController@homenajes')->name("homenajes");
Route::get('/page7','WebsiteController@acercade')->name("acercade");

Route::get('/request/status/{name}/{status}','WebsiteController@accountRequest')->name("request.status");
#Muestra vista notificando se requiere verificacion de correo 
Route::get('/email/status/{email}','WebsiteController@checkEmail')->name('email.status');



Route::get('/waiting','Auth\LoginController@waiting')->name('waiting');



//McNerio Routes 
Route::get('/login','Auth\LoginController@showLoginForm')->middleware('guest');
Route::post('/login','Auth\LoginController@login')->name('login');
Route::post('/logout','Auth\LoginController@logout')->name('logout');

# Carga el perfil de un usuario invitado
Route::get('/perfil/{idUser}','ProfileController@index')->name('profile.show');
# Carga la informacion completa para el usuario 
Route::get('/profile/information/{id}','ProfileController@information')->name('profile.information');
#Muestra la vista para editar un postevent especifico en apartado publico  
Route::get('/postedit/{postid}','ProfileController@editPostEvent')->name('profile.edit.item')->middleware('auth');
#Muestra la vista para mostrar un postevent especifico en apartado publico 
Route::get('/postshow/{postid}','ProfileController@showPostEvent')->name('profile.show.item');



//Routes para el administrador 
Route::get('/admin/home','DashboardController@index')->name('dashboard');
Route::get('/admin/content','DashboardController@content')->name('content');
Route::get('/admin/search','DashboardController@search')->name('admin.search'); //dado que hay otra para el cliente 

# Crea o actualiza  recurso del tipo reseña, [Homenaje o Biografias]
Route::post('/memories','MemoriesController@upsert')->name("memory.store")->middleware('auth','adroles');
#Muestra pantalla con items reseñas, con opcion de crear nuevo para el administrador
Route::get('/admin/memories','MemoriesController@indexadmin')->name('memories.index.admin');
#Muestra un formulario limpio o muestra para actualiza para el administrador
Route::get('/admin/memories/create','MemoriesController@create')->name('memories.create.admin');
#Recupera lista de homenajes/biografias para el administrador 
Route::get('/admin/memories/all','MemoriesController@getAllAdmin')->name('memories.all.admin');
#Muestra un Elemento especifico para el administrador 
Route::get('/admin/memories/{id}','MemoriesController@showadmin')->name('memories.show.admin');
#Recupera un elemento con peticion ajax 
Route::get('/memories/find/{id}','MemoriesController@find')->name('memories.find');
#Recupera lista de homenajes/biografias para el apartado public 
Route::get('/memories/all','MemoriesController@getAllPublic')->name('memories.all');
#AJAX 1 Elimina un elemento de  con todos sus medios(files,video.image) asociados 
Route::delete('/memories/{id}','MemoriesController@destroy')->name("memories.destroy"); //middleware in controller


# view | admin -  
Route::get('/admin/recursos','RecursosController@indexadmin')->name('recursos.index.admin')->middleware('auth','adroles');
# view | admin -  
Route::get('/admin/recursos/create','RecursosController@createadmin')->name('recursos.create.admin')->middleware('auth');
# view | admin -  
Route::get('/admin/recursos/{id}','RecursosController@showadmin')->name('recursos.show.admin')->middleware('auth');
# endpoint - save or update Recurso 
Route::post('/resource','RecursosController@upsert')->name("resource.store")->middleware('auth','adroles');
# endpoint - get all items Recursos 
Route::get('/resources','RecursosController@getall')->name('resources.all');
Route::get('/resource/{id}','RecursosController@find')->name('resources.find');
#AJAX 1 Elimina un elemento de  con todos sus medios(files,video.image) asociados 
Route::delete('/resource/{id}','RecursosController@destroy')->name("resouce.destroy")->middleware('auth','adroles');


Route::get('/admin/populars','DashboardController@populars')->name('populars');
Route::get('/admin/users','DashboardController@users')->name('users');
Route::get('/admin/categories','DashboardController@rubros')->name('rubros'); //categories and tags 
Route::get('/admin/roles','DashboardController@roles')->name('roles');
Route::get('/admin/users/config/{id}','DashboardController@infoUser')->name('user.info');
Route::get('/admin/post/edit/{id}','DashboardController@editElement')->name('admin.edit.item');
Route::get('/admin/post/show/{id}','DashboardController@showElement')->name('admin.edit.item');

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
# Establece un recurso de tipo publicacion como popular o no popular 
#Route::post('post/setPopular','PostEventController@setPostPopular')->name('post.set.popular')->middleware('auth','adroles');
# Establece el estado de un recurso de tipo publicacion, con estados de approval, review. deleted
#Route::post('post/setState','PostEventController@switchStatePost')->name('post.set.state')->middleware('auth','adroles');


# Almacena una nueva categoria 
Route::post('categories','CategoriesController@store')->name('cat.store')->middleware('auth','adroles');

#Obtiene la lista de todos los roles con los conteos de los permisos que posee
Route::get('roles','RolesController@index')->name("roles.index")->middleware('auth','adroles');
#Obtiene la lista de permisos relacionadas al rol solicitado 
Route::get('roles/{id}','RolesController@show')->name("roles.show")->middleware('auth','adroles');
//Actualiza, asocia o desvincula un permiso dentro del rol 
Route::put('roles/{id}','RolesController@update')->name("roles.update")->middleware('auth','adroles');

#Actualiza una etiqueta 
Route::put('tags/{id}','TagsController@update')->name("tag.update")->middleware('auth','adroles');
Route::post('tags','TagsController@store')->name('tags.store')->middleware('auth','adroles');
#Obtiene todas las etiquetas mediante el paso de una categoria , solo es necesario que este logeado sea rol o podria ser un invitado 
Route::get('tags/byCategory/{id}','TagsController@tagsByCategory')->name('tag.select')->middleware('auth');
#Guarda una nueva imagen de perfil del usuario logeado 
Route::post('user/uploadprofileimg','UsersController@uploadProfileImg')->middleware('auth');
#Elimina unaimagen de perfil del usuario, si es la que actualmente tiene de perfil setea la por defecto 
Route::delete('/user/deleteprofileimg/{id}','UsersController@dropprofileimg')->middleware('auth');
#Establece una imagen de perfil ya existente para un usuario
Route::put('/user/selectimgperfil/{id}','UsersController@selectimgperfil')->middleware('auth');
#Obtiene los notificadores, usuario, eventos, events para los paneles notificaciones en la pagina principal del admin 
#Esta ruta ya tiene los middleware adroles desde el constructor 
Route::get('/notifiers','DashboardController@notifiers')->name("notifiers");
#Crea o actualiza  un elemento, publicacion o evento con sus medios digitales (UPSERT)
Route::post('postevent','PostEventController@upsert')->name('postevent.store')->middleware('auth');
#Obtiene la informacion de un elemento/ evento/publicacion con todas sus relaciones 
Route::get('postevent/{id}','PostEventController@find')->name('post.show');
#AJAX 1 Elimina un recurso de tipo publicacion o evento con todos sus medios(files,video.image) asociados 
Route::delete('postevent/{id}','PostEventController@destroy')->name("postevent.destroy")->middleware('auth');
#Obtiene los items cercanos a una coordenda 
Route::get('/post/nearby','PostEventController@nearby')->name('post.nearby');

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


#Rutas para API Google
Route::get('/post/placesquery','PostEventController@places')->middleware('auth');
Route::get('/post/geoquery','PostEventController@geodecoding')->middleware('auth');