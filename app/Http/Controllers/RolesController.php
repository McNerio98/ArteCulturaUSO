<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission as Cap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RolesController extends Controller
{
    
    /**
     *  Ya cuenta con el middleware auth y roles solo administradores 
     */
    public function index(){

        //Verificando permisos 
        if(! Auth::user()->can('ver-roles')){
            $salida["msg"] = "Operación denegada";
            return $salida;
        }

        $salida = [
            "code" => 0,
            "data" => null,
            "msg" => ''
        ];
        
        $caps =  Cap::all();
        $roles = DB::select("SELECT r.id AS role_id, r.name AS role_name, count(cp.name) AS count_caps from roles r left join role_has_permissions rp on rp.role_id = r.id left join permissions cp on cp.id = rp.permission_id group by r.id");

        $salida["code"] = 1;
        $salida["data"] = [
            "roles" => $roles,
            "caps" => $caps
        ];
        $salida["msg"] = "Proccesed Successfully";

        return $salida;
    }

    /**
     * Obtiene una lista de permisos asociada a un rol determinado 
     */
    public function show(Request $request, $id){
        $output = [
            "code" => 0,
            "data" => null,
            "msg" => ''
        ];

        //Verificando permisos 
        if(!Auth::user()->can('ver-roles')){
            $output["msg"] = "Operación denegada";
            return $output;
        }

        $rol = Role::find($id);

        if(!$rol){
            $output["msg"] = "El rol no existe";
            return $output;
        }

        $output["code"] = 1;
        $output["data"] = $rol->permissions()->get();
        $output["msg"] = "Proccesed Successfully";
        
        return $output;
    }

    public function update(Request $request,$id){

        $output = [
            "code" => 0,
            "data" => null,
            "msg" => ''
        ];        

        if(!Auth::user()->can('asignar-permisos')){
            $output["msg"] = "Operación denegada";
            return $output;
        }


        //acction modificar un permiso del rol 
        if($request->alter_caps){
            $validator = Validator::make($request->all(),[
                'cap_id' => 'required',
                'is_checked' => 'required'
            ]); 

            if($validator->fails()){
                $output["msg"] = "Parametros incompletos";
                return $output;
            }

            //obteniendo el rol 
            $rol = Role::find($id);
            if(! $rol){
                $output["msg"] = "El rol no existe";
                return $output;
            }

            if(trim($rol->name) == "Invitado"){
                /**Se asegura por cualquier intento */
                $output["msg"] = "Operación restringida, este apartado no es modificable";
                return $output;
            }

            //obteniendo el permiso 
            $cap = Cap::find($request->cap_id);
            if(! $cap){
                $output["msg"] = "El permiso no existe, recargue el sitio";
                return $output;
            }

            if($request->is_checked){ //removiendo 
                $rol->revokePermissionTo($cap->name);
            }else{ //agregando 
                $rol->givePermissionTo($cap->name);
            }
            
            $output["code"] = 1;
            $output["msg"] = "Action complete";
            $output["data"] = $cap;
            return $output;
        }



        //obteniendo el permiso 

        return $salida;
    }

}
