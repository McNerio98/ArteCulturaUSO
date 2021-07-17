<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Tags_OnProfiles;
use App\Roles;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RequestAcountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Here is a mess bro!, I found making in this way ;)

        $nuevoUsuario = new User;

        $salida = [
            'codeStatus'  => 0,
            'msg'         => '',
            'operation'   => '',
            'objectData'  => null
        ];

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'telephone' => 'required',
            'rubros' => 'required',
            'artistic_name' => 'required'
        ]);

        if($validator->fails()){
            return $salida['msg'] = "Valores imcompletos, intente de nuevo";
        }

        ///Aqui vamos por la transaccion loco

        DB::beginTransaction();

        try{
            $nuevoUsuario->name = $request->name;
            $nuevoUsuario->email = $request->email;
            $nuevoUsuario->username = strstr($request->email, '@', true);
            $nuevoUsuario->password = Hash::make('123456789');
            $nuevoUsuario->telephone = $request->telephone;
            $nuevoUsuario->status = 'request';

            $nuevoUsuario->save();
            $nuevoUsuario->assignRole('Invitado');

            $profile = new Profile;

            $profile->artistic_name = $request->artistic_name;
            $profile->content_desc = "";
            $profile->rubros = $request->rubros;
            $profile->count_posts = 0;
            $profile->count_evebts = 0;
            $profile->user_id = $nuevoUsuario->id;
            $profile->save();

            $tagsOnProfile = new Tags_OnProfiles;
            $tagsOnProfile->user_id = $nuevoUsuario->id;
            $tagsOnProfile->tag_id = $request->rubros;
            $tagsOnProfile->save();


            DB::commit();
            $salida['codeStatus'] = 1;
            $salida['msg'] = "Solicitud enviada";
            $salida['operation'] = 'created';
            $salida['objectData'] = $nuevoUsuario;
            return $salida;
        }catch(\Exception $e){
            DB::rollback();
            $salida['msg'] = "Error: " . $e;
            return $salida;
        }








    }

    public function storeWithValidate(Request $request){
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
        ]);

        $salida = [
            "mensaje" => "OK" 
        ];

        return $salida;
    }

    public function storeWithValidateJson(Request $request){
        $salida = [
            'msg' => 'Todo ok'
        ];

        $input = $request->all();

        $messages = [
            'required' => 'The :attribute field is mc required',
            'email.required' => "Necesitamos saber tu correo plox",
            'username.unique' => 'El :attribute ya existe'
        ];

        $rules = [
            'name' => 'required|max:12|min:3',
            'tel' => 'required|numeric|min:0|not_in:0'
            //'rubros' => 'required|min:2',
            //'email' => 'required',
            //'username' => 'required|unique:users'
        ];

        $validator = Validator::make($input, $rules, $messages);
        
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()]);
        }else{
            return $salida;
        }
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
        //
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

    public function indexRoles(Request $request, $id){

        $user = Auth::user();

        $salida = [
            "code" => 1,
            "msg" => "Actualizando el usuario con ".$id . " poniendo el nombre de ".$request->name,
            "data" => Role::all(),
            "extra" => "Soy el usuario ".$user->name." con el identificador ".$user->id 
        ];


        return $salida;
    }
}
