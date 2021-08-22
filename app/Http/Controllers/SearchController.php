<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Category;

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

        //por defecto muestra todos los artistas 
        $params = null;
        //Si almenos un parametro se paso es porque viene de otra busqueda originada en otra seccion 
        if($request->id_filter || $request->label || $request->type_search){
            $params = [
                "id_filter" => $request->id_filter,
                "label" => $request->label,
                "type_search" => $request->type_search,
            ];
        }

        $cats = Category::all();

        return view("busquedas",['filter_search' => $params,'cats' => $cats]);
    }

    public function execSearch(Request $request){
        $salida = [
            "code" => 0,
            "msg"=> "",
            "data" => null,
            "extra" => ""
        ];

        //Valida que el type_search simpre sea cat, tag, custom, all, si no es ninguno de esos tres se envia error 
        $validator = Validator::make($request->all(), [
            'id_filter' => "required",
            'type_search' => 'required', //cat, tag, custom, all
            'init_paginate' => 'required' 
        ]);

        //Label es requerido solo si es diferente de type_search = all
        //ya que a traves de este label se hace la busqueda 
        if($request->type_search != "all" && !$request->label){
            $salida["msg"] = "Valores incompletos 1";
            return $salida;
        }
        
        $salida["parametros"] = $request->all();
        

        if($validator->fails()){
            $salida["msg"] = "Valores incompletos 2";
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
        //si no se paso identtificadores se extraen los identificadores 
        //tambien se valida que sea diferente a extraer todos los perfiles 
        #For type_search--> cat, tag, custom 
        if($result_idens === 0 && $type_seatch != "all"){ 
            $salida["extra"] .= "|Obteniendo los identificadores ";
            if($target_filter === null){
                #Buscando por nombres de artistas
                $salida["extra"] .= "|Buscando por nombre de artista ";
                $call_exec = "call FetchIdentifiers('$label',true,false,false,false)";
                $idens =  DB::select($call_exec);
                $target_filter = $idens[0]->IdsProfiles != null ? "profiles" : null;
                $result_idens = $idens[0]->IdsProfiles;
            }

            if($target_filter === null){
                #Buscando por nombres de categorias 
                $salida["extra"] .= "|Buscando nombre de categoria";
                $call_exec = "call FetchIdentifiers('$label',false,false,true,false)";      
                $idens =  DB::select($call_exec);
                $target_filter = $idens[0]->IdsCategories != null ? "cat" : null;
                $result_idens = $idens[0]->IdsCategories;
            }

            if($target_filter === null){
                #Buscando por nombres de tags  
                $salida["extra"] .= "|Buscando nombre tags";
                $call_exec = "call FetchIdentifiers('$label',false,false,false,true)";        
                $idens =  DB::select($call_exec);      
                $target_filter = $idens[0]->IdsTags != null ? "tag" : null;
                $result_idens = $idens[0]->IdsTags;
            }

            #Buscando por nombre de publicacion 
            #Este falta, considerar implementarlo
        }else{
            $salida["extra"] .= "|No se llamo FetchIdentifiers ";
            $target_filter = $type_seatch; //tag or cat, all, default, validar que sean unas de estas tres  
        }
        
        $salida["extra"] .= "|Este es typo de filtro(target_filter) ".$target_filter;
        $salida["extra"] .= "|Este es identificador objetivo(result_idens) ".$result_idens;

        $items      = null;
        $offset     = $paginate["from"] - 1;
        $limit        = $paginate["per_page"];

        //Si ya tiene la paginacion se omite calcularla nuevamente 
        //IMPORTANTE
        $init_pagination = boolval($request->init_paginate); //ajustar aqui 
        switch($target_filter){
            case "all": {
                if($init_pagination){
                    $salida["extra"] .= "| Inicializando paginacion para todos los perfiles";
                    $call_items             = "call ProfilesAll(0,0,true)";
                    $result                     = DB::select($call_items);
                    $paginate["total"] = intval($result[0]->calc_total);                    
                }
                $call_items                 = "call ProfilesAll($limit,$offset,false)";
                $items                         = DB::select($call_items);                
                break;
            }
            case "profiles": {
                if($init_pagination){
                    $salida["extra"] .= "| Inicializando paginacion para perfiles especificos ";
                    $call_items             = "call ProfilesById($result_idens,0,0,true)";
                    $result                     = DB::select($call_items);
                    $paginate["total"] = intval($result[0]->calc_total);                    
                }                
                $call_items                 = "call ProfilesById($result_idens,$limit,$offset,false)";
                $items                         = DB::select($call_items);                                
                break;
            }
            case "cat": { //Busqueda de categorias 
                    $salida["extra"] .= "|Buscando por categoria";
                    if($init_pagination){
                        $salida["extra"] .= "|Inicializando paginacion para busqueda por categoria";
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
                    $salida["extra"] .= "|Buscando por etiqueta";
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

}
