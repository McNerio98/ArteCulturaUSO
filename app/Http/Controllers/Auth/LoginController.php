<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Str;
use App\MediaProfile;
use App\EmailUserCheck;

class LoginController extends Controller
{

	public function __construct(){
		$this->middleware('guest',['only' =>'showLoginForm']);
        $this->middleware('auth',['only'=>'waiting']);
	}

    public function verifyAccount($token){
        $check = EmailUserCheck::where('token',$token)->first();
        $code =  404; //El correo no se ha podido identificar 
        if(! is_null($check)){
            $user = $check->user;
            if(is_null($user->email_verified_at)){
                $user->email_verified_at = date('Y-m-d H:i:s');
                $user->save();
                $code = 1;
            }else{
                $code = 2;
                $msg = "Tu correo electrónico ya está verificado. Ahora puede iniciar sesión.";
            }
            
        }

        return redirect()->route("request.status",[$user->name,$code]);
    }

	public function showLoginForm(){
		return view('auth.login');
	}

    public function waiting(){
        return view('waiting-confirm');
    }

    public function login(){

        //Redirecciona automaticamente 
        $credentials = $this->validate(request(),[
            'username'  => 'required|string|min: 2',
            'password'  => 'required|string| min: 2'
        ]);

        $username = request()->username;
        $password = request()->password;

        Auth::attempt(['email' => $username, 'password' => $password]);
        if(! Auth::check()){
            Auth::attempt(['username' => $username, 'password' => $password]);
        }

        if(Auth::check()){
            // validar 
            $current_user = Auth::user();

            if($current_user->status == 'request'){
                return redirect()->route('waiting');    
            }

            //generando token de acceso 
            //$api_token_access = (string) Str::uuid();
            //quitar esto cuando este completo toda la migracion
            //$current_user->api_token = $api_token_access;

            $mtx_name = explode(" ",trim($current_user->name));
            $name_user_short = "";
            $name_user = "";
            if(count($mtx_name) >= 2){
                $name_user_short = $mtx_name[0][0].$mtx_name[1][0];
                $name_user =$mtx_name[0]." ".$mtx_name[1]; 
            }else{
                $name_user_short = $mtx_name[0][0].$mtx_name[0][1];
                $name_user =$mtx_name[0];
            }

            $media = MediaProfile::find($current_user->img_profile_id);

            session([
                'name_cur_user' => $name_user,
                'name_cur_user_short' => $name_user_short,
                'media_profile_user' => $media->path_file,
                ]);
             

            $current_user->save();
            if($current_user->hasRole('Invitado')){
                return redirect()->route('profile.show',['idUser'=>$current_user->id]); 
            }else{
                //aqui tendria que extraerlos de la db, porq puede hallan registrado nuevos
                //$user->hasRole(['editor', 'moderator'])
                return redirect()->route('dashboard');    
            }
        }

        return back()->withErrors(["no_match"=>"Credenciales incorrectas"])->withInput(request(['username','password']));
        //return back()->withErrors(['username' => trans('auth.failed')])->withInput(request(['username']));
    }


    public function logout(){
        $path = '/';
        $current_user = Auth::user();

        if(!$current_user)
            return redirect($path);

        if($current_user->hasRole('Invitado')){
            $path = '/';    
        }

        Auth::logout();
        session()->forget('cur_user_token_access'); //eliminando la variabledel token
    	return redirect($path);
    }

}
