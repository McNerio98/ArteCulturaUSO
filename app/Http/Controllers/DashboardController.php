<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\PostEvent;
use App\Helper\UsersHelper;

class DashboardController extends Controller
{

	public function __construct(){
		//Todos requieren estar logeados 
		$this->middleware('auth');
		//adroles verifica que no sea un invitado el que esta intentado ver la dashboard 
		$this->middleware('adroles');
	}

    public function index(){
		$request_users = UsersHelper::usersRequest();
    	return view('admin.home' , ['ac_option' =>'home' , 'request_users' => $request_users]);
	}

	//MY CONTENT OPTION 
    public function content(){
		$request_users = UsersHelper::usersRequest();
    	return view('admin.postevents.index' , ['ac_option' =>'content' , 'request_users' => $request_users]);
	}

	//SEARCH PAGE  
    public function search(){
		$request_users = UsersHelper::usersRequest();
    	return view('admin.search' , ['ac_option' =>'search' , 'request_users' => $request_users]);
	}	
	

	
	//USERS OPTION	
    public function users(){
		if( ! Auth::user()->can('ver-usuarios')){
            return redirect()->route('dashboard');
        };
		$request_users = UsersHelper::usersRequest();
    	return view('admin.users' , ['ac_option' =>'usuarios' , 'request_users' => $request_users]);
	}

	public function rubros(){
		if( ! Auth::user()->can('ver-rubros')){ 
            return redirect()->route('dashboard');
        };
		$request_users = UsersHelper::usersRequest();
		return view("admin.rubros", ['ac_option' =>'rubros' , 'request_users' => $request_users]); 
	}

    public function parameters(){
		
		if( ! Auth::user()->hasRole('SuperAdmin')){ //poner esto en los de arriba 
            return redirect()->route('dashboard');
        };
		$request_users = UsersHelper::usersRequest();		
		return view('admin.config-options' , ['ac_option' =>'parameters' , 'request_users' => $request_users]);
    }
	
	public function roles(){
		if( ! Auth::user()->can('ver-roles')){ //poner esto en los de arriba 
            return redirect()->route('dashboard');
        };
		$request_users = UsersHelper::usersRequest();
		return view('admin.roles' , ['ac_option' =>'roles' , 'request_users' => $request_users]);
	}

    public function infoUser($id){
		//Si el usuario no tiene los permisos y tampoco es su perfin el que desea acceder 
		//se redireccionar 
        if( ! Auth::user()->can('ver-usuarios') && Auth::user()->id != $id){
            return redirect()->route('dashboard');
        };
		$request_users = UsersHelper::usersRequest();
        $roles = Role::all();

        return view("admin.config-user",['id_user_cur' => $id,'all_roles' =>$roles,'ac_option' =>'usuarios' , 'request_users' => $request_users]);
    }

	public function editElement($id){
		$e = PostEvent::find($id);
		if(!$e){
			return redirect()->route('dashboard');
		}
		
		if(!Auth::user()->can('editar-publicaciones') &&  intval($e->creator_id) !==  intval(Auth::user()->id)){
			return redirect()->route('dashboard');
		}
		$request_users = UsersHelper::usersRequest();
		return view("admin.postevents.edit-item",['request_users' => $request_users,'id_elem_edit' => $id,'ac_option' =>'null']);
	}

	public function showElement($id){
		$e = PostEvent::find($id);
		if(!$e){
			return redirect()->route('dashboard');
		}		

		$request_users = UsersHelper::usersRequest();
		return view("admin.postevents.show-item",['request_users' => $request_users,'id_elem_edit' => $id,'ac_option' =>'null']);
	}
	
	//Access via AJAX 
	public function notifiers(){
		$salida = [
			"code" => 0,
			"msg" => "",
			"data" => null
		];

		$posts 		  =	PostEvent::where("type_post","post")->count();
		$events 	= PostEvent::where("type_post","event")->count();
		$requests 	= User::where("status","request")->where('active',true)->count(); //se puede cambiar por otro 
		$users 		   = User::where('status','enabled')->where('active',true)->count();

		$salida["data"] = [
			"posts" => $posts,
			"events" => $events,
			"requests" => $requests,
			"users" => $users
		];
		$salida["code"] = 1;
		$salida["msg"] = "Request complete";

		return $salida;
	}

	
}
