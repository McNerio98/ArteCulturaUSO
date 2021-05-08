<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index($patter_search){
        parse_str($patter_search, $vals);
        //validar estructura aqui 

        $data = [
            "type_search"=>$vals["type"],
            "pattern_search"=>$vals["pattern"],
            "cat_id"=>$vals["category_id"]
        ];

        return view("busquedas",['parametros' => $data]);
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
