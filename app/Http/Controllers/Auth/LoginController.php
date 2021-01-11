<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Str;

class LoginController extends Controller
{

	public function __construct(){
		$this->middleware('guest',['only' =>'showLoginForm']);
        $this->middleware('auth',['only'=>'waiting']);
	}

	public function showLoginForm(){
		return view('auth.login');
	}

    public function waiting(){
        return view('waiting-confirm');
    }

    public function login(){
        $credentials = $this->validate(request(),[
            'username'  => 'required|string',
            'password'  => 'required|string'
        ]);

        if(Auth::attempt($credentials)){
            // validar 
            $current_user = Auth::user();

            if($current_user->status == 'request'){
                return redirect()->route('waiting');    
            }

            //generando token de acceso 
            $api_token_access = (string) Str::uuid();
            $current_user->api_token = $api_token_access;

            session(['cur_user_token_access' => $api_token_access]);
            
            $current_user->save();
            if($current_user->hasRole('Invitado')){
                return redirect()->route('profile');
            }else{
                //aqui tendria que extraerlos de la db, porq puede hallan registrado nuevos
                //$user->hasRole(['editor', 'moderator'])
                return redirect()->route('dashboard');    
            }
        }

        return back()->withErrors(['username' => trans('auth.failed')])->withInput(request(['username']));
    }


    public function logout(){
        $path = '/';
        $current_user = Auth::user();

        if(!$current_user)
            return redirect($path);

        if($current_user->hasRole('Invitado')){
            $path = '/login';    
        }

        Auth::logout();
        session()->forget('cur_user_token_access'); //eliminando la variabledel token
    	return redirect($path);
    }

}
