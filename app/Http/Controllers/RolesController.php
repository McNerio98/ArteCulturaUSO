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

        //Verificando permisos 
        if(!Auth::user()->can('ver-roles') || !Auth::user()->can('editar-roles')){
            $salida["msg"] = "Operación denegada";
            return $salida;
        }

        $salida = [
            "code" => 0,
            "data" => null,
            "msg" => ''
        ];

        $rol = Role::find($id);

        if(! $rol){
            $salida["msg"] = "El rol no existe";
            return $salida;
        }

        $salida["code"] = 1;
        $salida["data"] = $rol->permissions()->get();
        $salida["msg"] = "Proccesed Successfully";
        
        return $salida;
    }

    public function update(Request $request,$id){

        if(!Auth::user()->can('ver-roles') || !Auth::user()->can('editar-roles')){
            $salida["msg"] = "Operación denegada";
            return $salida;
        }

        $salida = [
            "code" => 0,
            "data" => null,
            "msg" => 'Hola'
        ];

        //acction modificar un permiso del rol 
        if($request->alter_caps){
            $salida["extra"] = "El alter caps se definio"; // <=====
            $validator = Validator::make($request->all(),[
                'cap_id' => 'required',
                'is_checked' => 'required'
            ]); 

            if($validator->fails()){
                $salida["msg"] = "Parametros incompletos";
                return $salida;
            }

            //obteniendo el rol 
            $rol = Role::find($id);
            if(! $rol){
                $salida["msg"] = "El rol no existe";
                return $salida;
            }

            if($rol->name == "Invitado"){
                $salida["msg"] = "Operacion no permitida";
                return $salida;
            }

            //obteniendo el permiso 
            $cap = Cap::find($request->cap_id);
            if(! $cap){
                $salida["msg"] = "El permiso no existe, recargue el sitio";
                return $salida;
            }

            if($request->is_checked){ //removiendo 
                $salida["extra"] = "El permiso existre dentro del rol, se removera"; // <=====
                $rol->revokePermissionTo($cap->name);
            }else{ //agregando 
                $salida["extra"] = "El permiso no existe del rol, se agrega"; // <=====
                $rol->givePermissionTo($cap->name);
            }
            $salida["code"] = 1;
            $salida["msg"] = "Action complete";
            $salida["data"] = $cap;
            return $salida;
        }



        //obteniendo el permiso 

        return $salida;
    }

}
