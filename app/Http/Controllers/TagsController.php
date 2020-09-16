<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Tag;

class TagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = DB::table('tags')
                    ->whereNotIn('name',['Artistas','Promotores','Expresiones','Escuelas','Recursos'])
                    ->get();
        
        return $result;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $salida = [
            'codeStatus'  => 0,
            'msg'         => '',
            'objectData'  => null];
            
        if(! isset($request->nameTag)){
            $salida['msg'] = "Valores imcompletos, Recargue la pagina";
            return $salida;
        }

        $tag = new Tag();
        $tag->name = $request->nameTag;

        if(! $tag->save()){
            $salida['msg'] = "ERROR al guardar la Etiqueta";
            return $salida;   
        }

        $salida['codeStatus'] = 1;
        $salida['msg'] = "Tag Creada";
        $salida['objectData'] = $tag;

        return $salida;  
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $salida = [
            'codeStatus'  => 0,
            'msg'         => '',
            'objectData'  => null];
            
        if(! isset($request->new_name,$id)){
            $salida['msg'] = "Valores imcompletos, Recargue la pagina";
            return $salida;
        }
        
        $new_name = $request->new_name;
        
        $tag = Tag::find($id);

        if(!$tag){
            $salida['msg'] = "La etiqueta no existe";
            return $salida;
        }
        
        $tag->name = $new_name;
        
        if(! $tag->save()){
            $salida['msg'] = "ERROR al establecer el nuevo estado";
            return $salida;   
        }

        $salida['codeStatus'] = 1;
        $salida['msg'] = "Usuario Modificado";
        $salida['objectData'] = $tag;

        return $salida;        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $salida = [
            'codeStatus'  => 0,
            'msg'         => '',
            'objectData'  => null];
            
        if(! isset($id)){
            $salida['msg'] = "Valores imcompletos, Recargue la pagina";
            return $salida;
        }
        
        $tag = Tag::find($id);
        $tag->delete();

        $salida['codeStatus'] = 1;
        $salida['msg'] = "Tag Eliminado";
        $salida['objectData'] = $tag;
        
        return $salida;
    }
}
