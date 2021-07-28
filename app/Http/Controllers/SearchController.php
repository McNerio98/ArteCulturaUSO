<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
    public function index(Request $request){
        
        //Si hay elementos NULOS se validan en la vista
        //En la vista verifica que sean numericos y diferentes de 0  por esa razon se verifican aqui 
        /*
        ** sp : (String =>  post,profiles,both) indica que tipo de busqueda se realizada 
        ** lbl : (String) contiene el label del valor que se pudo ingresar en la busqueda desde otra parte 
        ** tag: (Number) Identificaro de la etiqueta que fue ingresada desde otra seccion 
        ** cat: (Number) Identificador de la categoria que fue ingresada desde otra seccion 
        */

        $params = null;
        //Si almenos un parametro se paso es porque viene de otra busqueda originada en otra seccion 
        if($request->id_filter || $request->label || $request->type_search){
            $params = [
                "id_filter" => $request->id_filter,
                "label" => $request->label,
                "type_search" => $request->type_search,
            ];
        }

        return view("busquedas",['filter_search' => $params]);
    }

    public function execSearch(Request $request){
        $salida = [
            "code" => 0,
            "msg"=> "",
            "data" => null
        ];

        $validator = Validator::make($request->all(), [
            'id_filter' => "required",
            'label' => 'required',
            'type_search' => 'required',
        ]);

        if($validator->fails()){
            $salida["msg"] = "Valores incompletos";
            return $salida;
        }

        $id_filter = $request->id_filter;
        $label = $request->label;
        $type_seatch = $request->type_search;

        //si viene con un id buscar
        if(intval($id_filter) != 0){
            if($type_seatch == "cat"){
                $salida["msg"] = "Realizando por categoria con este id : ".$id_filter;
            }

            if($type_seatch == "tag"){
                $salida["msg"] = "Realizando por etiqueta con este id ".$id_filter;
            }
        }

        return $salida;
    }

    public function byCategory($id){
        $salida = [
            "code" => 0,
            "msg"=> "",
            "data" => null
        ];

        $results = DB::select("call getDataSearchAC('NAN','NAN',false,$id)");

        $salida["code"] = 1;
        $salida["data"] =$results;
        return $salida;
    }
}
