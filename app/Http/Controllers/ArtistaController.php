<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArtistaController extends Controller
{
    function show($id = null){
        $artista = array(
            'name' => "Juan Carlos",
            'lastname' => "Servantes",
            'nombreartistico' => "El pirolito",
            'descripcion' => "Soy un payasito de Sonsonate, con la 
                                sonrisa bien alegre, dando carcajadas a todos"
        );
        return view('artista.index')->with($artista);
    }
}
