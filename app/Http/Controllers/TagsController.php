<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Tag;
use Illuminate\Support\Facades\Validator;

class TagsController extends Controller
{

    public function tagsByCategory($id_cat){
        $salida = [
            "code" => 0,
            "data" => null,
            "msg" => null
        ];
        
        if(! isset($id_cat)){
            $salida["msg"] = "Id de categoria requerido";
            return $salida;
        }

        if(intval($id_cat) <= 0){
            $salida["msg"] = "El id no es valido";
            return $salida;
        };

        $tags = Tag::where('category_id',$id_cat)->get();

        $salida = [
            "code" => 1,
            "data" => $tags,
            "msg" => "OK"
        ];
        
        return $salida; 
    }

    public function index()
    {
        $salida = [
            "code" => 0,
            "data" => null,
            "msg" => ""
        ];
        $salida["code"] = 1;
        $salida["data"]  = Tag::all();

        return $salida;
    }


    public function store(Request $request){
        $salida = [
            "code" => 0,
            "data" => null,
            "msg" => null
        ];

        $validator = Validator::make($request->all(),[
            "tag_name" => "required",
            "category_id" =>"required"
        ]);

        if($validator->fails()){
            $salida["msg"] = "Valores imcompletos";
            return $salida;
        }

        $tag = new Tag();
        $tag->name = $request->tag_name;
        $tag->category_id  = $request->category_id;

        if(! $tag->save()){
            $salida['msg'] = "Error al guardar la Etiqueta";
            return $salida;
        }

        $salida = [
            "code" => 1,
            "data" => Tag::find($tag->id),
            "msg" => "Ok"
        ];        

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
            "code" => 0,
            "data" => null,
            "msg" => null
        ];

        if(! isset($id)){
            $salida["msg"] = "Parametros imcompletos";
            return $salida;
        }

        $validator = Validator::make($request->all(),[
            "tag_name" => "required",
        ]);
        if($validator->fails()){
            $salida["msg"] = "Valores imcompletos";
            return $salida;
        };

        $tag = Tag::find($id);
        if(!$tag){
            $salida['msg'] = "La etiqueta no existe";
            return $salida;
        }

        $tag->name = $request->tag_name;
        if(! $tag->save()){
            $salida['msg'] = "Error al actualizar etiqueta";
            return $salida;
        }

        $salida = [
            "code" => 1,
            "data" => $tag,
            "msg" => "Ok"
        ];

        return $salida;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getBySeccion(){
        $query = DB::select("select t.id, t.name as tag, c.name as category from tags t, categories c where t.category_id = c.id;");
        $collection = collect($query);

        $grouped = $collection->groupBy('category');
        return $grouped->toArray();
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
            "code" => 0,
            "data" => null,
            "msg" => null
        ];
        if(! isset($id)){
            $salida['msg'] = "Valores imcompletos";
            return $salida;
        }

        $tag = Tag::find($id);
        if(! $tag->delete()){
            $salida["msg"] = "Error al eliminar la etiqueta";
            return $salida;
        }

        $salida = [
            "code" => 1,
            "data" => $tag,
            "msg" => "Ok"
        ];
        return $salida;
    }
}
