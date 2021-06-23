<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{

	public function __construct(){
		//Todos requieren estar logeados 
		$this->middleware('auth');
	}

	private function userRequest(){
		return User::join("media_profiles AS mp","mp.id","=","users.img_profile_id")->select("users.*","mp.path_file")->where("users.status","request")->get();
	}

    public function index(){
		//Si el usuario es un invitado, no debe tener acceso a este apartado 
		if(Auth::user()->hasRole("Invitado") ){  
            return redirect()->route('inicio');
        };		
		$request_users = $this->userRequest();
    	return view('admin.home' , ['ac_option' =>'home' , 'request_users' => $request_users]);
	}
	
	public function rubros(){
		if( ! Auth::user()->can('ver-rubros')){ 
            return redirect()->route('dashboard');
        };
		$request_users = $this->userRequest();
		return view("admin.rubros", ['ac_option' =>'rubros' , 'request_users' => $request_users]); 
	}

	//Falta Elementos Destacados
	//Falta Homenajes 
	
    public function users(){
		if( ! Auth::user()->can('ver-usuarios')){ //poner esto en los de arriba 
            return redirect()->route('dashboard');
        };
		$request_users = $this->userRequest();
    	return view('admin.users' , ['ac_option' =>'usuarios' , 'request_users' => $request_users]);
	}
	
	public function roles(){
		if( ! Auth::user()->can('ver-roles')){ //poner esto en los de arriba 
            return redirect()->route('dashboard');
        };
		$request_users = $this->userRequest();
		return view('admin.roles' , ['ac_option' =>'roles' , 'request_users' => $request_users]);
	}

    public function infoUser($id){
        if( ! Auth::user()->can('ver-usuarios')){ //poner esto en los de arriba 
            return redirect()->route('dashboard');
        };
        $request_users = $this->userRequest();
        $roles = Role::all();

        return view("admin.config-user",['id_user_cur' => $id,'all_roles' =>$roles,'ac_option' =>'usuarios' , 'request_users' => $request_users]);
    }
	
	
}
