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


class UsersController extends Controller
{
    public $path_store_profiles =  "/files/profiles/";

    public function __construct(){
        //$this->middleware('auth',['only'=>['configUser']]);
        $this->middleware('auth:api',['only'=>[
            'configUserData',
            'updateConfigUser',
            'uploadProfileImg'
            ]]);
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

    public function validateEmail($id,$email){
        $salida = [
            "code" => 0,
            "msg" => "",
            "data" => null
        ];

        $user = User::where('email',trim($email))->where('id','<>',$id)->first();
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

    public function validateUsername($id,$username){
        $salida = [
            "code" => 0,
            "msg" => "",
            "data" => null,
            'extra' => null
        ];
        
        $user = User::where('username',trim($username))->where('id','<>',$id)->first();
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

        //Si no tiene los permisos o no es el usuario propietario de la cuenta 
        if(! Auth::user()->can('configurar-usuario') && Auth::user()->id != $id){
            $salida["msg"] = "No posee permisos para esta acción";
        }

        $validator = Validator::make($request->all(),[
            'operation' => [
                'required',
                Rule::in([
                    'enable-user',
                    'disable-user',
                    'delete-user',
                    'update-credentials',
                    'edit-meta' //for example description 
                ]),
            ],
        ]);


        if($validator->fails()){
            $salida['msg'] = "Inconsistencia de datos, intente más tarde";
            return $salida;
        }

        try{
            switch($request->operation){
                case 'edit-meta': {
                    $persist = UserMeta::updateOrCreate(
                        ['user_id' => $id,'key' => trim($request->conf_key)],
                        ['value' => trim($request->conf_value)]
                    );
                    if(!$persist){
                        $salida["msg"] = "La acción no pudo ser completada, intente más tarde";
                    }else{
                        $salida["code"] = 1;
                        $salida["msg"] = "Accion completa con exito";
                    }

                    break;
                }
                case 'enable-user': {
                    $user = User::find($id);
                    if(!$user){
                        $salida["msg"] = "El usuario no existe";
                        break;
                    }

                    $user->status = 'enabled';

                    if(!$user->save()){
                        $salida["msg"] = "Error al establecer el nuevo estado";
                        break;
                    }
                    $salida["code"] = 1;
                    $salida["msg"] = "Accion Completada";
                    break;
                }
                case 'disable-user': {
                    $user = User::find($id);
                    if(!$user){
                        $salida["msg"] = "El usuario no existe";
                        break;
                    }

                    $user->status = 'disabled';

                    if(!$user->save()){
                        $salida["msg"] = "Error al establecer el nuevo estado";
                        break;
                    }
                    $salida["code"] = 1;
                    $salida["msg"] = "Accion Completada";
                    break;
                }                
                case 'update-credentials': {
                    $user = User::find($id);
                    if(!$user){
                        $salida["msg"] = "El usuario no existe";
                        break;
                    }

                    //guardando raw pass 
                    $persist = UserMeta::updateOrCreate(
                        ['user_id' => $id,'key' => 'user_profile_rawpass'],
                        ['value' => $request->raw_pass]
                    );

                    if(!$persist){
                        $salida["msg"] = "La acción no pudo ser completada, intente más tarde";
                        break; 
                    }
                    //ya estan validado que no se repita nombre de usuario y email 
                    $user->username = trim($request->username);
                    $user->email = trim($request->email);
                    $user->password =  Hash::make($request->raw_pass);
                    if(! Role::find($request->role)){
                        $salida["msg"] = "El rol selecionado no existe";
                        break;
                    }
                    if(isset($request->status)){
                        $user->status = trim($request->status);
                    }
                    $user->assignRole(trim(Role::find($request->role)->name));

                    if(!$user->save()){
                        $salida["msg"] = "Error en actualizar credenciales";
                        break;
                    }

                    if($request->send_email == true){
                        //enviando email 
                    }

                    $salida["code"] = 1;
                    $salida["msg"] = "Credenciales Actualizadas";
                    break;
                }
            }
        }catch(Throwable $ex){
            $salida["msg"] = "Error en la Operacion php version 7";
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

        $per_page = ($request->per_page === null)?6:$request->per_page;

        $valid_range = array(6,12,24);
        if(! in_array($per_page,$valid_range)){
            $per_page = 6;
        }
		
        $result = DB::table('users')
        ->join("model_has_roles","model_has_roles.model_id","=","users.id")
        ->join("roles","roles.id","=","model_has_roles.role_id")
        ->leftJoin('media_profiles','media_profiles.id','=','users.img_profile_id')
        ->select("users.id","roles.name as role","users.name","users.img_profile_id","users.email",
            "users.username","users.telephone","users.rubros","users.status","media_profiles.path_file AS img_profile")
        ->paginate($per_page);

        return [
            'paginate' =>[
                    'total' =>$result->total(),
                    'current_page'  => $result->currentPage(),
                    'per_page'      => $result->perPage(),
                    'last_page'     => $result->lastPage(),
                    'from'          => $result->firstItem(),
                    'to'            => $result->lastPage(),
            ],
            'users' => $result

        ];
        return $users;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
            'email.unique' => 'El correo electrónico ya existe'
        ];

        $validator = Validator::make($request->all(),[
            'name' => 'required|max:200|min:2',
            'email' => 'required|email|unique:users',
            //'telephone' => 'required|numeric|digits_between:8,9',  //8 sin guion, 9 con guion 
            'telephone' => 'required: max: 9',
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

            DB::commit();
            $salida = [
                'code' => 1,
                'data' => $new_user,
                'msg' => 'Usuario registrado',
                'errors' => null
            ];
        }catch(\Exception $e){
            DB::rollback();
            $salida['msg'] = "Error de transacción Póngase en contacto con soporte técnico."  . $e->getMessage();
        }
        return $salida;
    }
}
