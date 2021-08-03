<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserMeta;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UsersMetasController extends Controller
{

    public function __construct(){
        //$this->middleware('auth',['only'=>['configUser']]);
        $this->middleware('auth:api',['only'=>[
            'store'
            ]]);
    }

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

        $keys = ['user_profile_description','user_profile_address','user_profile_notes'];
        if(! in_array($request->info_key,$keys)){
            $salida["msg"] = "Operacion no valida";
            return $salida;
        }

        $meta = UserMeta::where('key',$request->info_key)->where('user_id',$request->user_id)->first();

        //usar transacciones 

        if($meta){//ya existia, se modifica  
            $meta->value = $request->info_value;
        }else{//no existe, se crea  
            $meta = new UserMeta();
            $meta->user_id = $request->user_id;
            $meta->key = $request->info_key;
            $meta->value = $request->info_value;
        }

        if(! $meta->save()){
            $salida["msg"] = "Error al crear el valor del usuario";
            return $salida;            
        }

        $salida = [
            "code" => 1,
            "data" => $meta,
            "msg" => 'Ok'
        ];

        return $salida;
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
}
