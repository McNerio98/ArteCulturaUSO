<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserMeta;
use Illuminate\Support\Facades\Validator;

class UsersMetasController extends Controller
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

        $salida = [
            "code" => 0,
            "data" => null,
            "msg" => null
        ];

        $validator = Validator::make($request->all(),[
            "user_id" => "required|numeric", 
            "meta_key" => "required",
            "meta_value" => "required"
        ]);

        if($validator->fails()){
            $salida["msg"] = "Valores imcompletos";
            return $salida;
        }

        /*
        **Aqui verificar, si el use_id que viene en la request es igual al user_id del usuario logeado 
        ** si es asi, se sigue ejecutando, de no ser asi validar que el usuario logeado tenga los permisos
        ** si los tiene sigue, se manda un mensaje de error 
        */
        if(! isset($request->user_id)){
            $salida["msg"] = "Usuario actual no identificado";
            return $salida;            
        }

        $meta = UserMeta::where('key',$request->meta_key)->where('user_id',$request->user_id)->first();

        //usar transacciones 

        if($meta){//ya existia, se modifica  
            $meta->value = $request->meta_value;
        }else{//no existe, se crea  
            $meta = new UserMeta();
            $meta->user_id = $request->user_id;
            $meta->key = $request->meta_key;
            $meta->value = $request->meta_value;
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
