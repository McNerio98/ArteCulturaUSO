<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{
	public function __construct(){
		$this->middleware('api');
	}

    public function index(Request $request){
		$user = auth()->guard('api')->user();

        $salida = [
            'codeStatus'  => 0,
            'msg'         => 'six',
            'operation'   => '',
			'objectData'  => $user
		];
					

		
		return $salida;
		
		/*if( ! $user->hasRole('Invitado')){
			$salida['msg'] = "Perfil es solo para invitados";
		}
		
		$id =$user->id;
		
		//verificar si tiene cuenta de tipo invitado 
		$result = DB::table('profile')->select('count_post','count-evenbts')->where('id','=',$id)->get();
		
        $salida = [
            'codeStatus'  => 0,
            'msg'         => '',
            'operation'   => '',
			'objectData'  => $result
		];

		return $salida;*/
	}
	

}
