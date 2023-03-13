<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\ConfigOption;

class ParamController extends Controller
{
    public function index(){
        $output = [
            "code" => 0,
            "msg" => "",
            "data" => null
        ];

		if( ! Auth::user()->hasRole('SuperAdmin')){ 
            $output["msg"] = "Operación denegada";
            return $output;
        };        

        $params = ConfigOption::all();

        $output["code"] = 1;
        $output["data"] = $params;
        $output["msg"] = "Registros Recuperados";
        
        return $output;
    }

    public function update(Request $request){
        $output = [
            "code" => 0,
            "msg" => "",
            "data" => null
        ];

		if( ! Auth::user()->hasRole('SuperAdmin')){ 
            $output["msg"] = "Operación denegada";
            return $output;
        };        

        $option = ConfigOption::where('option_name',$request->option_name)->first();
        if(!$option ){
            $output["msg"] = "Parámetro no encontrado";
            return $output;            
        }

        $valid_flags = array('A','D'); //A(active): true, D(disabled): false
        if($option->option_type == 'FLAG' && !in_array($request->option_value,$valid_flags)){
            $output["msg"] = "Inconsistencia en parámetro bandera";
            return $output;                    
        }

        if(trim($request->option_name) == 'FILE_SIZE'){
            if(!is_numeric($request->option_value) || intval($request->option_value) == 0){
                $output["msg"] = "Error, valores validos para este parametro: Numerico mayor a 0";
                return $output;                          
            }
        }




        $option->option_value = trim($request->option_value);
        if(!$option->save()){
            $output["msg"] = "Error en el guardado";
            return $output;
        }

        $output["code"] = 1;
        $output["msg"] = "Actualizado correctamente";
        return $output;        
    }
}
