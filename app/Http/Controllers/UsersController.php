<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\TagsOnProfile;
use App\UserMeta;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Storage;
use App\MediaProfile;
use App\EmailUserCheck;
use App\Mail\CredentialsUpdated;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\PostEvent;

class UsersController extends Controller
{
    public $path_store_profiles =  "/files/profiles/";

    public function noactive(){
		return view("user-noactive");
    }


	public function showPostEvent($id_post){
		$e = PostEvent::find($id_post);
		if(!$e){//No existe
			return redirect()->route('inicio');
		}		

		$user = $e->owner;
		if($user->active == false || $user->status == 'disabled'){
			return redirect()->route('inicio');
		}		


		return view("profile.showpost",["postid" => $id_post]);
	}


    public function uploadProfileImg(Request $request){
        $salida = [
            'code' => 0,
            'data' => null,
            'msg' => null
        ];

        $validator = Validator::make($request->all(), [
            'user_id' => "required",
            'img_profile_upload' => 'required'
        ]);

        if($validator->fails()){
            $salida["msg"] = "Valores incompletos";
            return $salida;
        }

        $valid_extens = ["jpeg", "jpg", "png","JPEG", "JPG", "PNG"];
        $file_extension = explode('/', mime_content_type($request->img_profile_upload))[1];
        if(! in_array($file_extension, $valid_extens)){
            $salida["msg"] = "Formato de imagen no admitido";
            return $salida;
        }

        if( Auth::user()->id != $request->user_id){
            $salida['msg'] = "Accion no permitida";
            return $salida;
        }
        
        try{
            date_default_timezone_set('America/El_Salvador');
            DB::beginTransaction();
            $img = $request->img_profile_upload;
            $data = substr($img, strpos($img, ',') + 1);
            $img_name = time().uniqid().".".$file_extension;
            $path_store = $this->path_store_profiles.$img_name;
            Storage::disk('local')->put($path_store,base64_decode($data));
            $media = MediaProfile::create([
                'user_id' => Auth::user()->id,
                'path_file' => $img_name
            ]);
            
            Auth::user()->img_profile_id = $media->id;
            Auth::user()->save();
            $salida["code"] = 1;
            $salida["data"] = $media;
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            //Si funciona, el getMessage recuperar la SQL
            //$e->getMessage()
            $salida['msg'] = "Error de transacción Póngase en contacto con soporte técnico.";
            return $salida;            
        }
        
        return $salida;
    }

	public function dropprofileimg($id){
        $output = [
            'code' => 0,
            'data' => null,
            'msg' => null
        ];

		$img = MediaProfile::find($id);
		if(!$img){
			$output["msg"] = "La imagen especificada no existe";
			return $output;
		}

		$owner = User::find($img->user_id);
		if(!$owner){
            $output["msg"] = "Inconsistencia de datos";
            return $output;			
		}
		
		if(!Auth::user()->can('configurar-usuarios') && $img->user_id != Auth::user()->id){
			$output["msg"] = "Operacion no permitida";
			return $output;
		}

		if($img->path_file == "default_img_profile.png"){
			$output["msg"] = "Operación no permitida";
			return $output;
		}

		$auxPath = $this->path_store_profiles.$img->path_file;
		DB::beginTransaction();
		try{
			if(!Storage::disk('local')->exists($auxPath)){
				throw new \Exception("Archivo fisico no encontrado para " . $img->path_file);
			}

            //Si la imagen eliminada es la que tiene de perfil se setea a la por defecto 
            if($owner->img_profile_id == $id){
                $defecto = MediaProfile::where('user_id',$owner->id)->where('path_file','default_img_profile.png')->first();
                if(!$defecto){
                    throw new \Exception("Inconsistencia de usuario al cambiar a imagen por defecto");
                }

                $owner->img_profile_id = $defecto->id;
                $owner->save();
            }

			if(!Storage::disk('local')->delete($auxPath)){
				throw new \Exception("No se pudo eliminar el archivo " . $img->path_file);
			}
			$img->delete();

            DB::commit();
            $output["code"] = 1;
            $output["msg"] = "Deleted Successfully";
		}catch(\Throwable $ex){
			DB::rollback();
			$output["msg"] = $ex->getMessage();
		}
		
		return $output;
	}

    public function selectimgperfil($id){
        $output = [
            'code' => 0,
            'data' => null,
            'msg' => null
        ];

		$img = MediaProfile::find($id);
		if(!$img){
			$output["msg"] = "La imagen especificada no existe";
			return $output;
		}

		$user = User::find($img->user_id);
		if(!$user){
            $output["msg"] = "Inconsistencia de datos";
            return $output;			
		}
		
		if(!Auth::user()->can('configurar-usuarios') && $img->user_id != Auth::user()->id){
			$output["msg"] = "Operacion no permitida";
			return $output;
		}

        $user->img_profile_id = $id;
        $user->save();

        $output["code"] = 1;
        $output["msg"] = "Imagen establecida";        
        return $output;
    }
    public function checkEmail($email){
        $salida = [
            "code" => 0,
            "msg" => "",
            "data" => null
        ];

        if(is_null($email)){
            $salida["msg"] = "Parámetro no valido ";
            return $salida;
        }

        if(strlen($email) == 0){
            $salida["msg"] = "Parámetro no valido ";
            return $salida;
        }
        
        $call_sp = "call checkEmail('{$email}')";
        $result_sp = DB::select($call_sp);
        $status_email = intval($result_sp[0]->status);

        if($status_email == 0){
            $salida["msg"] = "Ocurrio un error en la verificacion";
        }

        /*
            1 ---> Correo disponible 
            2 ---> Correo no disponible 
        */
        $check_data = [
            "code" => 2,
            "msg"
        ];

        if($status_email == 1){
            $check_data["code"] = 1;
            $check_data["msg"] = "Correo electrónico disponible";
        }else{
            $check_data["code"] = 2;
            $check_data["msg"] = "Correo electrónico no disponible";
        }

        $salida["code"] = 1;
        $salida["data"] = $check_data;
        $salida["msg"] = "Verificated Successfully";
        return $salida;
    }

    /**Funcion obsoleta al completar basada en procedimiento */
    public function validateEmail($id,$email){
        $salida = [
            "code" => 0,
            "msg" => "",
            "data" => null
        ];

        $user = User::where('email',trim($email))->where('id','<>',$id)->where("active","=",true)->first();
        if($user){
            $salida["code"] = 1;
            $salida["msg"] = "El correo existe";
            $salida["data"] = 1;
        }else{
            $salida["code"] = 1;
            $salida["msg"] = "El correo no existe";
            $salida["data"] = 0;
        }

        return $salida;
    }

    public function validateTelephone($id,$target){
        $salida = [
            "code" => 0,
            "msg" => "",
            "data" => null
        ];

        $user = User::where('telephone',trim($target))->where('id','<>',$id)->where("active","=",true)->first();
        if($user){
            $salida["code"] = 1;
            $salida["msg"] = "Ya existe el numero";
            $salida["data"] = 1;
        }else{
            $salida["code"] = 1;
            $salida["msg"] = "No existe el numero";
            $salida["data"] = 0;
        }

        return $salida;        
    }

    public function validateUsername($id,$username){
        $salida = [
            "code" => 0,
            "msg" => "",
            "data" => null,
            'extra' => null
        ];
        
        $user = User::where('username',trim($username))->where('id','<>',$id)->where("active","=",true)->first();
        $salida["extra"] = "Contenido, username : ".$username." iden: ".$id;
        if($user){
            $salida["code"] = 1;
            $salida["msg"] = "El nombre del usuario existe";
            $salida["data"] = 1;
        }else{
            $salida["code"] = 1;
            $salida["msg"] = "El nombre del usuario no existe";
            $salida["data"] = 0;
        }

        return $salida;
    }

    
    public function updateConfigUser(Request $request,$id){
        $salida = [
            'code' => 0,
            'data' => null,
            'msg' => ''
        ];

        if(!isset($id) || $id < 1){
            $salida["msg"] = "El id es requerido";
            return $salida;
        }

        //Si el administrador actual no tiene el permiso de configurar usuario
        //Si el administrador no tiene el permiso pero si es dueño de la cuenta si puede continuar 
        if(! Auth::user()->can('configurar-usuarios') && $id != Auth::user()->id){
            $salida["msg"] = "No posee permisos para esta acción";
            return $salida;
        }

        $validator = Validator::make($request->all(),[
            'operation' => [
                'required',
                Rule::in([
                    'enable-user',
                    'disable-user',
                    'delete-user', //set active = 0 
                    'update-credentials',
                    'edit-meta' //for example description 
                ]),
            ],
        ]);


        if($validator->fails()){
            $salida['msg'] = "Inconsistencia de datos, recargue el sitio";
            return $salida;
        }

        $user = User::find($id);
        if(!$user){
            $salida["msg"] = "El usuario no existe";
            return $salida;
        
        }
    
        try{
            DB::beginTransaction();
            switch($request->operation){
                case 'delete-user': {
                    $user->active = false;
                    $user->saveOrFail();//esta dentro del try el captura posible error 
                    break;
                }
                case 'edit-meta': {
                    $persist = UserMeta::updateOrCreate(
                        ['user_id' => $id,'key' => trim($request->conf_key)],
                        ['value' => trim($request->conf_value)]
                    );
                    break;
                }
                case 'enable-user': {
                    $user->status = 'enabled';
                    $user->saveOrFail();
                    break;
                }
                case 'disable-user': {
                    $user->status = 'disabled';
                    $user->saveOrFail();
                    break;
                }                
                case 'update-credentials': {
                    //guardando raw pass 
                    $persist = UserMeta::updateOrCreate(
                        ['user_id' => $id,'key' => 'user_profile_rawpass'],
                        ['value' => $request->raw_pass]
                    );
                    
                    //ya estan validado que no se repita nombre de usuario y email 
                    $user->username = trim($request->username);
                    $user->password =  Hash::make($request->raw_pass);

                    if(count(   explode(' ', $user->username)     ) >   1){
                        throw new \Exception('Nombre de usuario no puede contener espacios');
                    }
                    
                    //Solo los usuarios con el permiso pueden asignar roles.
                    if( Auth::user()->can('configurar-usuarios') ){
                        $roset = Role::find($request->role);
                        //$salida["extra"] = "Seiguio despues de no encontrarlo"; Si siguio 
                        if(!$roset){throw new \Exception("El rol selecionado no existe");}
                        // All current roles will be removed from the user and replaced by the array given
                        //podra tener un unico rol
                        $fresh_roles = [$roset->name];
                        $user->syncRoles($fresh_roles);
                        $user->is_admin = (trim($roset->name) != "Invitado") ? true:false;
                    }

                    if(isset($request->status)){
                        $user->status = trim($request->status);
                    }
                    $user->saveOrFail();
                    $user->refresh();
                    if($request->send_email == true){
                        $tmp_mail = trim($user->email);
                        $uitem = new \stdClass();
                        $uitem->name = $user->name;
                        $uitem->username = $user->username;
                        $uitem->password = $request->raw_pass;
                        $uitem->email = $user->email;
                        Mail::to($tmp_mail)->send(new CredentialsUpdated($uitem));
                    }

                    $salida["code"] = 1;
                    $salida["msg"] = "Credenciales Actualizadas";
                    break;
                }
            }

            $salida["code"] = 1;
            $salida["msg"] = "Operacion completa";
            DB::commit();
        }catch(\Throwable $ex){
            DB::rollback();
            //$salida["msg"] = "Error en la Operacion ";
            $salida["msg"] = "Error ".$ex->getMessage();

        }

        return $salida;
    }

    public function configUserData($id){
        $salida = [
            'code' => 0,
            'data' => null,
            'msg' => null
        ];

        if( ! Auth::user()->can('ver-usuarios')){
            $salida['msg'] = "No tiene los permisos ";
            return $salida;
        };

        $metas_get = ['user_profile_description'];
        if(Auth::user()->can('configurar-usuarios')){
            array_push($metas_get,'user_profile_rawpass');
        }

        $metas = UserMeta::whereIn('key',$metas_get)->where('user_id',$id)->get();

        $user = User::leftJoin("media_profiles","media_profiles.id","=","users.img_profile_id")->where("users.id","=",$id)->select("users.*","media_profiles.path_file AS img_profile")->first();
        $info = [
            'metas' => $metas,
            'user' => $user,
            'rol' => User::find($id)->roles->first()
        ];

        $salida = [
            'code' => 1,
            'data' => $info,
            'msg' => 'Ok'
        ];

        return $salida; 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $salida = [
            "code" => 0,
            "data" => null,
            "msg" => ""
        ];

        $per_page = ($request->per_page === null)?15:$request->per_page;
        $filter = ($request->filter === null)?'all':$request->filter;
        $valid_filters = array('all','enabled','disabled','request');
        if(! in_array($filter,$valid_filters)){
            $salida["msg"] = "Inconsistencia de datos, recargue el sitio";
            return $salida;
        }

		if( ! Auth::user()->can('ver-usuarios')){ 
            $salida["msg"] = "Acción no permitida";
            return $salida;
        };        
		
        $builder = DB::table('users')
        ->join("model_has_roles","model_has_roles.model_id","=","users.id")
        ->join("roles","roles.id","=","model_has_roles.role_id")
        ->leftJoin('media_profiles','media_profiles.id','=','users.img_profile_id')
        ->select("users.id","roles.name as role","users.name","users.img_profile_id","users.email",
            "users.username","users.telephone","users.rubros","users.status","media_profiles.path_file AS img_profile")
        ->whereNotNull('email_verified_at'); //only verified users

        switch($filter){
            case "enabled": {
                $builder->where("status","enabled");
                break;
            }
            case "disabled": {
                $builder->where("status","disabled");
                break;
            }
            case "request": {
                $builder->where("status","request");
                break;
            }
        }

        //by default get all if no pass extra filter
        $result = $builder->paginate($per_page);
        $salida["code"] = 1;
        $salida["data"] = [
            "pagination" => [
                'total' =>$result->total(),
                'current_page'  => $result->currentPage(),
                'per_page'      => $result->perPage(),
                'last_page'     => $result->lastPage(),
                'from'          => $result->firstItem(),
                'to'            => $result->lastPage(),                
            ],
            "items" => $result->items()
        ];

        return $salida;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $salida = [
            "code" => 0,
            "data" => null,
            "msg" => null
        ];

        $validator = Validator::make($request->all(),[
            "user_id" => "required|numeric", 
            "info_key" => "required",
            "info_value" => "required"
        ]);

        if($validator->fails()){
            $salida["msg"] = "Valores imcompletos";
            return $salida;
        }   
        
        if(Auth::user()->id != $request->user_id && ! Auth::user()->can('configurar-usuarios')){
            $salida["msg"] = "Operacion denegada";
            return $salida;
        }
        
        $keys = ['user_email','user_phone','user_other_name','user_nickname'];
        if(! in_array($request->info_key,$keys)){
            $salida["msg"] = "Operacion no valida";
            return $salida;
        }

        $user = User::find($request->user_id);

        switch($request->info_key){
            case "user_nickname": { //nombre artistico 
                $user->artistic_name = trim($request->info_value);
                break;
            }            
            case "user_email": {
                $user->email = trim($request->info_value);
                break;
            }
            case "user_phone": {
                $user->telephone = trim($request->info_value);
                break;
            }
            case "user_other_name": { //nombre del titular de la cuenta 
                $user->name = trim($request->info_value);
                break;
            }                                                        
        }

        $user->save();

        $salida["code"] = 1;
        $salida["msg"] = "Request complete";
        return $salida;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $salida = [
            'codeStatus'  => 0,
            'msg'         => '',
            'operation'   => '',
            'objectData'  => null];

        //this validations most be implements in all controllers function request
        if(! isset($request->operation,$id)){
            $salida['msg'] = "Valores imcompletos, Recargue la pagina";
            return $salida;
        }

        // Actions Allowed: Enabled, Disabled
        $operation = $request->operation;
        $valid_values = ['enabled','disabled'];
        if(! in_array($operation,$valid_values)){
            $salida['msg'] = "Inconsistencia en los Valores, Recargue la pagina";
            return $salida;
        }

        $user = User::find($id);

        if(!$user){
            $salida['msg'] = "El usuario No existe";
            return $salida;
        }

        $user->status = $operation;

        if(! $user->save()){
            $salida['msg'] = "ERROR al establecer el nuevo estado";
            return $salida;
        }

        $salida['codeStatus'] = 1;
        $salida['msg'] = "Usuario Modificado";
        $salida['operation'] = $operation;
        $salida['objectData'] = $user;

        return $salida;
    }


    public function requestAccount(Request $request)
    {
        $new_user = new User;
        $salida = [
            'code' => 0,
            'data' => null,
            'msg' => null,
            'errors' => null
        ];

        $validation_messages = [
            'email.unique' => 'El correo electrónico ya existe',
            'telephone.unique' => 'El numero de contacto ya existe'
        ];

        $validator = Validator::make($request->all(),[
            'name' => 'required|max:200|min:2',
            'email' => 'required|email|unique:users',
            //'telephone' => 'required|numeric|digits_between:8,9',  //8 sin guion, 9 con guion 
            'telephone' => 'required| max: 9|unique:users',
            'rubros' => 'required|numeric|min:1',
            'artistic_name' => 'required|max:100|min:2'            
        ],$validation_messages);

        if($validator->fails()){
            $salida['msg'] = "Valores inconsistentes, intente nuevamente";
            $salida['errors'] = $validator->errors();
            return $salida;
        }



        try{
            DB::beginTransaction();
            $new_user->name = $request->name;
            $new_user->email = $request->email;
            //$new_user->username = strstr($request->email, '@', true);
            //$new_user->password = Hash::make('123456789');
            $new_user->telephone = $request->telephone;
            $new_user->artistic_name = $request->artistic_name;
            $new_user->status = 'request';
            $new_user->save();
            $new_user->assignRole('Invitado');

            //agregando imagen por defecto 
            $profile_img = MediaProfile::create([
                'user_id' => $new_user->id,
                'path_file' => 'default_img_profile.png'
            ]);	            
            $new_user->img_profile_id = $profile_img->id;
            $new_user->save();
            $tag_profile = new TagsOnProfile;
            $tag_profile->user_id = $new_user->id;
            $tag_profile->tag_id = $request->rubros;
            $tag_profile->save();

            $tmp_data = new \stdClass();
            $tmp_data->token = ((string) Str::uuid()).$new_user->artistic_name[0].$new_user->artistic_name[1];
            $tmp_data->name = $new_user->name;

            $echeck = EmailUserCheck::create([
                'user_id' => $new_user->id,
                'token' => $tmp_data->token
            ]);

            Mail::to($new_user->email)->send(new VerifyEmail($tmp_data));
            
            DB::commit();
            $salida = [
                'code' => 1,
                'data' => $new_user,
                'msg' => 'Usuario registrado',
                'errors' => null
            ];
        }catch(\Exception $ex){
            DB::rollback();
            //$salida["msg"] = $ex->getMessage();
            $salida['msg'] = "Error de transacción Póngase en contacto con soporte técnico.";
        }
        return $salida;
    }
}
