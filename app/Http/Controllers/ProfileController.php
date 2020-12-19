<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Profile;


class ProfileController extends Controller
{
	public function __construct(){
		$this->middleware('auth:api');
	}

    public function index(Request $request){
		//$user = auth()->guard('api')->user();


        $salida = [
            'codeStatus'  => 0,
            'msg'         => 'six',
            'operation'   => '',
			'objectData'  => null
		];

		$user = Auth::user();



		/*if( ! $user->hasRole('Invitado')){
			$salida['msg'] = "Perfil es solo para invitados";
		}*/



		//verificar si tiene cuenta de tipo invitado

		$infoProfile = [
			'profile' => null,
			'general' => null
		];

		$profile = Profile::where('user_id',$user->id)->first();

		$infoProfile['profile'] = $profile;
		//$result = DB::table('profiles')->select('count_posts','count_evebts','content_desc')->where('user_id','=',$id)->get();

        $salida = [
            'codeStatus'  => 1,
            'msg'         => 'Informacion recuperada',
            'operation'   => 'READ',
			'objectData'  => $infoProfile
		];

		return $salida;
	}


}
