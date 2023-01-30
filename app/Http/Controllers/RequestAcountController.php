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


use App\Mail\CredentialsUpdated;
use Illuminate\Support\Facades\Mail;


class RequestAcountController extends Controller
{


    //Metodo con propositos de pruebas unicamente
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

    //Metodo con propositos de pruebas unicamente
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
