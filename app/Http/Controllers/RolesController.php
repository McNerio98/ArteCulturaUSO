<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission as Cap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RolesController extends Controller
{
    
    public function index(){
        //Verificar que tenga los permisos correspondientes
        //permiso --> confgurar-roles
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

        return $salida;
    }

    public function show(Request $request, $id){
        //Verificar que tenga los permisos correspondientes
        //permiso --> confgurar-roles        
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
        $salida["msg"] = "Request Success";
        
        return $salida;
    }

    public function update(Request $request,$id){
        //Verificar que tenga los permisos correspondientes
        //permiso --> confgurar-roles        
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
