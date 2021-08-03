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
            'type_search' => 'required', //cat, tag or default, validar aqui  
        ]);

        //Valida que el type_search simpre sea cat tag o default, si no es ninguno de esos tres se envia error 

        if($validator->fails()){
            $salida["msg"] = "Valores incompletos";
            return $salida;
        }

        $id_filter          = intval($request->id_filter);
        $label              = $request->label;
        $type_seatch = $request->type_search;        
        //Si no se paso la variable la inicia con 1, sino hace la conversion del que trae 
        $page_aux = $request->page == null ? 1 : intval($request->page);
        //Si la conversion fue 0, es porque es el parseo no fue exitoso, se establece a uno 
        if($page_aux === 0){    $page_aux = 1;  }


        $paginate = [
            "total" => 0,
            "current_page" => 0,
            "per_page" => 0,
            "last_page" => 0,
            "from" => 0,
            "to" => 0
        ];    
        $paginate["per_page"] = 15;
        $paginate["current_page"] = $page_aux;
        $paginate["from"] = ( ($paginate["current_page"] * $paginate["per_page"]) - $paginate["per_page"]) +1;





        $target_filter  = null;
        $result_idens = null;
        // $id_filter debe ser un arreglo de numeros 
        $result_idens = $id_filter;
        if($result_idens === 0){ //si no se paso identtificador se extraen los identificadores 

            if($target_filter === null){
                #Buscando por nombres de artistas
                $call_exec = "call FetchIdentifiers('$label',true,false,false,false)";
                $idens =  DB::select($call_exec);
                $target_filter = $idens[0]->IdsProfiles != null ? "profiles" : null;
                $result_idens = $idens[0]->IdsProfiles;
            }

            if($target_filter === null){
                #Buscando por nombres de categorias 
                $call_exec = "call FetchIdentifiers('$label',false,false,true,false)";      
                $idens =  DB::select($call_exec);
                $target_filter = $idens[0]->IdsCategories != null ? "cat" : null;
                $result_idens = $idens[0]->IdsCategories;
            }

            if($target_filter === null){
                #Buscando por nombres de tags  
                $call_exec = "call FetchIdentifiers('$label',false,false,false,true)";        
                $idens =  DB::select($call_exec);      
                $target_filter = $idens[0]->IdsTags != null ? "tag" : null;
                $result_idens = $idens[0]->IdsTags;
            }

        }else{
            $target_filter = $type_seatch; //tag or cat, default, validar que sean unas de estas tres  
        }


        $items      = null;
        $offset     = $paginate["from"] - 1;
        $limit        = $paginate["per_page"];

        //Si ya tiene la paginacion se omite calcularla nuevamente 
        $init_pagination = true;
        switch($target_filter){
            case "cat": { //Busqueda de categorias 
                    if($init_pagination){
                        //calculando total, no es necesario limit y offset 
                        $call_items             = "call ProfilesByCategory($result_idens,0,0,true)"; 
                        $result                     = DB::select($call_items);
                        $paginate["total"] = intval($result[0]->calc_total);
                    }
                    $call_items                 = "call ProfilesByCategory($result_idens,$limit,$offset,false)"; 
                    $items                         = DB::select($call_items);
                break;
            }
            case 'tag': {
                    if($init_pagination){
                        //calculando total, no es necesario limit y offset 
                        $call_items             = "call ProfilesByTag($result_idens,0,0,true)"; 
                        $result                     = DB::select($call_items);
                        $paginate["total"] = intval($result[0]->calc_total);
                    }
                    $call_items                 = "call ProfilesByTag($result_idens,$limit,$offset,false)"; 
                    $items                         = DB::select($call_items);                
                break;
            }
        }

        //Segun la paginacion usada en todos los modulos, la variable "to" es la misma que la ultima pagina 
        $paginate["last_page"] = intval(ceil($paginate["total"] / $paginate["per_page"]));
        $paginate["to"] = $paginate["last_page"];         


        $salida["data"] = $items;
        $salida["pagination"] = $paginate;
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
