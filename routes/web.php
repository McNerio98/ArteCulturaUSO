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
//Route::get('/page1','WebsiteController@artitas')->name("artistas");
//Route::get('/page2','WebsiteController@promotores')->name("promotores");
//Route::get('/page3','WebsiteController@escuelas')->name("escuelas");
Route::get('/page4','WebsiteController@recursos')->name("recursos");
Route::get('/page5','WebsiteController@biografias')->name("biografias");
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
Route::get('/perfil/{id}','ProfileController@index')->name('profile.show');
# Carga la informacion completa para el usuario 
Route::get('/profile/information/{id}','ProfileController@information')->name('profile.information');
#Muestra la vista para editar un post 
Route::get('/perfil/{idUser}/post/edit/{idPost}','ProfileController@editElement')->name('profile.edit.item')->middleware('auth');



//Routes para el administrador 
Route::get('/admin/home','DashboardController@index')->name('dashboard');
Route::get('/admin/content','DashboardController@content')->name('content');
Route::get('/admin/search','DashboardController@search')->name('admin.search'); //dado que hay otra para el cliente 

#Muestra pantalla con items reseñas, con opcion de crear nuevo para el administrador
Route::get('/admin/memories','MemoriesController@index')->name('memories.index.admin');
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

# Crea un nuevo recurso del tipo reseña, [Homenaje o Biografias]
Route::post('memories','MemoriesController@store')->name("memory.store")->middleware('auth','adroles');
#Obtiene los usuarios dentro de la plataforma, con filtros para todos los usuarios, solicitudes, habilitados, no activos 
Route::get('users','UsersController@index')->name('users.fetch')->middleware('auth','adroles');
# Guarda la fotografia de presentacion de la categoria que se esta editando 
Route::post('/categories/saveImgPresentation','CategoriesController@changeImgPresentation')->middleware('auth','adroles');
# Establece un recurso de tipo publicacion como popular o no popular 
Route::post('post/setPopular','PostEventController@setPostPopular')->name('post.set.popular')->middleware('auth','adroles');
# Establece el estado de un recurso de tipo publicacion, con estados de approval, review. deleted
Route::post('post/setState','PostEventController@switchStatePost')->name('post.set.state')->middleware('auth','adroles');
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
Route::post('user/uploadImgProfile','UsersController@uploadProfileImg')->middleware('auth');
#Obtiene los notificadores, usuario, eventos, events para los paneles notificaciones en la pagina principal del admin 
#Esta ruta ya tiene los middleware adroles desde el constructor 
Route::get('/notifiers','DashboardController@notifiers')->name("notifiers");
#Almacena un elemento, publicacion o evento con sus medios digitales 
Route::post('postevent','PostEventController@store')->name('postevent.store')->middleware('auth');
#Obtiene la informacion de un elemento/ evento/publicacion 
Route::get('postevent/{id}','PostEventController@show')->name('post.show');
#Actualiza un elemento, publicacion o evento con sus medios digitales 
Route::put('postevent/{id}','PostEventController@update')->name('postevent.update')->middleware('auth');
#AJAX 1 Elimina un recurso de tipo publicacion o evento con todos sus medios(files,video.image) asociados 
Route::delete('postevent/{id}','PostEventController@destroy')->name("postevent.destroy")->middleware('auth');


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
