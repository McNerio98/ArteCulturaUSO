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
Route::get('/soporte','WebsiteController@supportusers')->name('support');
Route::get('/request/status/{name}/{status}','WebsiteController@accountRequest')->name("request.status");
#Muestra vista notificando se requiere verificacion de correo 
Route::get('/email/status/{email}','WebsiteController@checkEmail')->name('email.status');



Route::get('/waiting','Auth\LoginController@waiting')->name('waiting');




Route::get('/login','Auth\LoginController@showLoginForm')->name('page.login')->middleware('guest');
Route::post('/login','Auth\LoginController@login')->name('login');
Route::post('/logout','Auth\LoginController@logout')->name('logout');


#Muestra la vista para mostrar un postevent especifico en apartado publico 
Route::get('/postshow/{postid}','UsersController@showPostEvent')->name('profile.show.item');



//Routes para el administrador 
Route::get('/admin/home','DashboardController@index')->name('dashboard');
Route::get('/admin/content','DashboardController@content')->name('content');
Route::get('/admin/search','DashboardController@search')->name('admin.search'); //dado que hay otra para el cliente 








/*:::::::::::::::::::::::::::::::::::::: ADMIN PANEL ::::::::::::::::::::::::::::::::::::::*/

/*------------------------------Biografias/Homenajes------------------------------*/
#Blade View | Muestra pantalla con items reseñas, con opcion de crear nuevo para el administrador
Route::get('/admin/memories','MemoriesController@indexadmin')->name('memories.index.admin');
#Blade View | Muestra un vista pra formulario crear o actualizar 
Route::get('/admin/memories/create','MemoriesController@create')->name('memories.create.admin');
#Ajax Request |  Recupera lista de homenajes/biografias para el administrador 
Route::get('/admin/memories/all','MemoriesController@getAllAdmin')->name('memories.all.admin');
#Blade View | Muestra un Elemento especifico para el administrador 
Route::get('/admin/memories/show/{id}','MemoriesController@showadmin')->name('memories.show.admin');
# AJAX Request | Crea o actualiza  recurso del tipo reseña, [Homenaje o Biografias]
Route::post('/memories','MemoriesController@upsert')->name("memory.store")->middleware('auth','adroles');
#AJAX Request | Recupera un elemento 
Route::get('/memories/find/{id}','MemoriesController@find')->name('memories.find');
#Recupera lista de homenajes/biografias para el apartado public 
Route::get('/memories/all','MemoriesController@getAllPublic')->name('memories.all');
#AJAX 1 Elimina un elemento de  con todos sus medios(files,video.image) asociados 
Route::delete('/memories/{id}','MemoriesController@destroy')->name("memories.destroy")->where('id', '[0-9]+')->middleware('auth','adroles');



/*------------------------------Recursos------------------------------*/
# Blade View | admin -  
Route::get('/admin/recursos','RecursosController@indexadmin')->name('recursos.index.admin')->middleware('auth','adroles');
# Blade View | admin -  
Route::get('/admin/recurso/show/{id}','RecursosController@showadmin')->name('recursos.show.admin')->middleware('auth');
# Blade View | admin -  
Route::get('/admin/recursos/create','RecursosController@createadmin')->name('recursos.create.admin')->middleware('auth');
# AJAX - save or update Recurso 
Route::post('/resource','RecursosController@upsert')->name("resource.store")->middleware('auth','adroles');
# AJAX - get all items Recursos for PUBLIC site 
Route::get('/resources','RecursosController@getAllPublic')->name('resources.all');
# AJAX - get all items Recursos for ADMIN site 
Route::get('/admin/resources','RecursosController@getAllAdmin')->name('resources.all.admin');
# AJAX Recupera los datos para el recurso especificado
Route::get('/resource/{id}','RecursosController@find')->where('id', '[0-9]+')->name('resources.find');
#AJAX | Obtiene los Tipos de recursos (No requiere filtro de seguridad)
Route::get('/resources/tipos','RecursosController@tipos')->name('resources.tipos');
#AJAX 1 Elimina un elemento de  con todos sus medios(files,video.image) asociados 
Route::delete('/resource/{id}','RecursosController@destroy')->name("resouce.destroy")->middleware('auth','adroles');


/*------------------------------Profile------------------------------*/
# Blade View | Carga el perfil de un usuario invitado
Route::get('/perfil/{idUser}','ProfileController@index')->name('profile.show');
# Carga la informacion completa para el usuario 
Route::get('/profile/information/{id}','ProfileController@information')->name('profile.information');


/*------------------------------User------------------------------*/
Route::get('/admin/users','DashboardController@users')->name('users');
# AJAX Request | Obtiene los usuarios dentro de la plataforma, con filtros para todos los usuarios, solicitudes, habilitados, no activos 
Route::get('users','UsersController@index')->name('users.fetch')->middleware('auth','adroles');
# Blade View | Apartado muestra la informacion del usuario con apartados de configuracion 
Route::get('/admin/users/config/{id}','DashboardController@infoUser')->name('user.info');
# AJAX Request| Actualiza la configuracion del usuario 
Route::put('/user/updateConfig/{id}','UsersController@updateConfigUser')->name("user.updateconf")->middleware('auth','adroles');
# Blade View | Muestra mensaje de cuenta desactivada
Route::get('/user/noactive','UsersController@noactive')->name('user.noactive');


/*------------------------------PostEvents------------------------------*/
# Blade View | Muestra la vista para editar un postevent especifico en apartado publico  
Route::get('/postedit/{postid}','PostEventController@editPostEvent')->name('profile.edit.item')->middleware('auth');
#AJAX Request | Crea o actualiza  un elemento, publicacion o evento con sus medios digitales (UPSERT)
Route::post('/postevent','PostEventController@upsert')->name('postevent.store')->middleware('auth');



/*------------------------------Roles y permisos------------------------------*/
#Obtiene la lista de todos los roles con los conteos de los permisos que posee
Route::get('roles','RolesController@index')->name("roles.index")->middleware('auth','adroles');
#AJAX Request | Obtiene la lista de permisos relacionadas al rol solicitado 
Route::get('roles/{id}','RolesController@show')->name("roles.show")->middleware('auth','adroles');
//Actualiza, asocia o desvincula un permiso dentro del rol 
Route::put('roles/{id}','RolesController@update')->name("roles.update")->middleware('auth','adroles');



Route::get('/admin/populars','DashboardController@populars')->name('populars');
Route::get('/admin/categories','DashboardController@rubros')->name('rubros'); //categories and tags 
Route::get('/admin/roles','DashboardController@roles')->name('roles');
Route::get('/admin/post/edit/{id}','DashboardController@editElement')->name('admin.edit.item');
Route::get('/admin/post/show/{id}','DashboardController@showElement')->name('admin.edit.item');

//Routes para busquedas 
Route::get('/search','SearchController@index')->name('search');



//Middleware addroles filtra que el usuario sea un administrador y no un invitado, si es invitado lo redirecciona 
Route::get('/admin/recientes','PostEventController@recientes')->name('items.recientes')->middleware('auth','adroles');


Route::get('/users/dataConfig/{id}','UsersController@configUserData')->name("user.dataconf")->middleware('auth','adroles');




/*------------------ Categories ------------------*/
# AJAX Request | Guarda la fotografia de presentacion de la categoria que se esta editando 
Route::post('/categories/saveImgPresentation','CategoriesController@changeImgPresentation')->middleware('auth','adroles');
# AJAX Request | Almacena una nueva categoria 
Route::post('categories','CategoriesController@store')->name('cat.store')->middleware('auth','adroles');
# AJAX Request | Elimina una categorias con su imagen de presentacion
Route::delete('category/{id}','CategoriesController@destroy')->name('category.destroy')->middleware('auth','adroles');

/*------------------ Tags ------------------*/
#AJAX Request | Actualiza una etiqueta 
Route::put('tags/{id}','TagsController@update')->name("tag.update")->middleware('auth','adroles');
# AJAX Request | Registra una nueva etiqueta
Route::post('tags','TagsController@store')->name('tags.store')->middleware('auth','adroles');
# AJAX Request | Elimina una etiqueta 
Route::delete('/tag/{id}','TagsController@destroy')->name('tag.destroy')->middleware('auth','adroles');
# AJAX Request | Obtiene todas las etiquetas mediante el paso de una categoria , solo es necesario que este logeado sea rol o podria ser un invitado 
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
#Obtiene la informacion de un elemento/ evento/publicacion con todas sus relaciones 
Route::get('postevent/{id}','PostEventController@find')->name('post.show');
#AJAX 1 Elimina un recurso de tipo publicacion o evento con todos sus medios(files,video.image) asociados 
Route::delete('postevent/{id}','PostEventController@destroy')->name("postevent.destroy")->middleware('auth');
#Obtiene los items cercanos a una coordenda 
Route::get('/post/nearby','PostEventController@nearby')->name('post.nearby');
#Obtiene los datos para el tablero de eventos 
Route::get('/tablero','PostEventController@tableevents')->name('table.events');

//Middleware que necesitan que el usuario este logeado 
#Guardar nueva etiqueta dentro del perfil 
Route::put('/profile/tags/{id}','ProfileController@updateTags')->middleware('auth');
#Eliminar etiqueta desde perfil 
Route::delete('/profile/deltag/{idu}/{idtg}','ProfileController@deleteTag')->middleware('auth');
#Guardar informacion del usuario, informacion como ['user_email','user_phone','user_other_name','user_nickname'];
Route::post('users','UsersController@store')->middleware('auth');
#Guarda cualquier meta dato del usuario
Route::post('usersmeta','UsersMetasController@store')->middleware('auth');




#Muestra la lista de elementos post y eventos del usuario parado como id 
Route::get('/postsevents/{id}','ProfileController@elements');



/*------------------ Promociones ------------------*/
# view | admin -  Return blade for List elements 
Route::get('/admin/promociones','PromocionesController@index')->middleware('auth','adroles')->name('promociones.admin');
# view | admin -  Return blade for new element 
Route::get('/admin/promociones/create','PromocionesController@create')->middleware('auth','adroles')->name('promociones.create.admin');
# view | admin -  Return blade for show specific element 
Route::get('/admin/promociones/show/{id}','PromocionesController@show')->middleware('auth','adroles')->name('promociones.show.admin');
#AJAX Request | get All elementos 
Route::get('/promociones','PromocionesController@getall')->name('promociones.all');
#AJAX Request | create or update element 
Route::post('/promocion','PromocionesController@upsert')->middleware('auth','adroles')->name('promociones.upsert');
#AJAX Request | get data element 
Route::get('/promocion/{id}','PromocionesController@find')->name('promociones.find');
#AJAX Request | delete element 
Route::delete('/promocion/{id}','PromocionesController@destroy')->name('promociones.destroy');

/*------------------ Procesos ------------------*/
Route::get('/admin/procesos','ProcesosController@index')->middleware('auth')->name('procesos.admin');
Route::post('/procesofechas','ProcesosController@resetdatesevent')->middleware('auth','adroles')->name('procesos.resetdates');
Route::post('/procesoemail','ProcesosController@testemail')->middleware('auth','adroles')->name('procesos.testemail');

/*------------------ Parametros del sistema ------------------*/
Route::get('/admin/params','DashboardController@parameters')->middleware('auth','adroles')->name('params.index');
Route::get('/parameters','ParamController@index')->middleware('auth','adroles')->name('params.all');
Route::patch('/parameters','ParamController@update')->middleware('auth','adroles')->name('params.update');

#Rutas para API Google
Route::get('/post/placesquery','PostEventController@places')->middleware('auth');
Route::get('/post/geoquery','PostEventController@geodecoding')->middleware('auth');


/*------------------ Installer Only Router ------------------*/
Route::get('/install/keyapplication','InstallerController@key_generate');
Route::get('/install/storagelink','InstallerController@storage_link');
Route::get('/install/clearconfig','InstallerController@clear_config');
Route::get('/install/clearcache','InstallerController@clear_cache');
Route::get('/install/clearroute','InstallerController@clear_route');


