<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Category;
use Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class CategoriesController extends Controller
{
    //
    public function index(){
        $salida = [
            "code" => 0,
            "data" => null,
            "msg" => null
        ];
        
        $categories = Category::all();
        $salida["code"] = 1;
        $salida["data"] = $categories;
        return $salida;
    }

    public function store(Request $request){
        $salida = [
            "code" => 0,
            "data" => null,
            "msg" => null
        ];

        $validator = Validator::make($request->all(),[
            "category_name" => "required"
        ]);

        if($validator->fails()){
            $salida["msg"] = "Valores imcompletos";
            return $salida;
        }

        $prev = Category::where('name',$request->category_name)->first();

        if($prev){
            $salida["msg"] = "El nombre de la categoria ya existe";
            return $salida;
        }

        $cat = new Category();
        $cat->name = $request->category_name;
        
        if(! $cat->save()){
            $salida["msg"] = "Error al guardar la categoria";
            return $salida;
        }

        $salida = [
            "code" => 1,
            "data" => Category::find($cat->id),
            "msg" => "Ok"
        ];        

        return $salida;
    }

    public function changeImgPresentation(Request $request){
        $salida = [
            "code" => 0,
            "data" => null,
            "msg" => null
        ];

        if(! Auth::user()->can('editar-rubros')){
            $salida["msg"] = "No posee permisos para esta acción";
            return $salida;
        }
        
        $validator = Validator::make($request->all(),[
            "id_category" =>"required",
            "current_name_img" => "required", //esto definira si se remplazao se crea uno nuevo
            "img_presentation" => "required"
        ]);

        if($validator->fails()){
            $salida["msg"] = "Valores imcompletos";
            return $salida;
        };

        $cat = Category::find($request->id_category);
        if(!$cat){
            $salida["msg"] = "Categoria no encontrada";
            return $salida;
        }

        DB::beginTransaction();
        try{

            $img = $request->img_presentation;

            $data = substr($img, strpos($img, ',') + 1);
            $img_name = uniqid().'.jpg';
            $path_store =  "files/categories/".$img_name;

            if(! Storage::disk('local')->put($path_store,base64_decode($data)) ){
                throw new \Exception("Error al guardan la imagen de presentation");
            }

            $prev_name = $cat->img_presentation; 
            $cat->img_presentation = $img_name;
            $cat->save();

            //Para categorias se usa una unica foto para los img default 
            //Eliminando fotografia anterior si es diferente de la default 
            if(trim($prev_name) !== "default_img_category.png"){
                if(! Storage::disk('local')->delete('files/categories/'.$prev_name) ){
                    throw new \Exception("No se logró eliminar la imagen previa");
                }
            }

            DB::commit();
            $salida = [
                "code" => 1,
                "data" => $img_name,
                "msg" => "Operation Complete"
            ];            
        }catch(\Throwable $ex){
            DB::rollback();
            $salida['msg'] = "Error al guardar imagen";            
        }

        return $salida;
    }
    
}
