<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArtistaController extends Controller
{
    public function requestget($id){
        $salida = [
            'code'  => 650,
            'data'  => null,
            'msg'   => 'llegando a la peticion get'
        ];

        $salida["data"] = "este es el id ".$id;
        return $salida;
    }

    public function requestpost($id){
        $salida = [
            'code'  =>650,
            'data'  =>  null,
            'msg'   => 'llegando a la peticion post'
        ];
        $salida["data"] = "este es el id ".$id;
        return $salida;
    }

}
