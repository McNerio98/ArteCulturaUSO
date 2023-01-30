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

    /**Save or Update */
    public function store(Request $request){
        $output = [
            "code" => 0,
            "data" => null,
            "msg" => null
        ];

        $validator = Validator::make($request->all(),[
            "category_id" => "required|numeric",
            "category_name" => "required|min: 2|max: 50"
        ]);

        if($validator->fails()){
            $output["msg"] = "Valores imcompletos";
            return $output;
        }


        $user = Auth::user();
        $isUpdate = (intval($request->category_id) != 0);

        if($isUpdate){
            $category = Category::find($request->category_id);
        }else{
            $category = new Category();
            $prev = Category::where('name',$request->category_name)->first();

            if($prev && !$isUpdate){
                $output["msg"] = "El nombre de la categoria ya existe";
                return $output;
            }            
        }

        if(!$category){//For update case only 
            $output["msg"] = "Ítem no encontrado";
            return $output;                   
        }

        if(!$isUpdate && !$user->can('crear-rubros')){
            $output["msg"] = "Operación denegada";
            return $output;
        }

        if($isUpdate && !$user->can('editar-rubros')){
            $output["msg"] = "Operación denegada";
            return $output;
        }


        $category->name = $request->category_name;
        
        if(! $category->save()){
            $output["msg"] = "Error al guardar los cambios en la categoria";
            return $output;
        }

        $output = [
            "code" => 1,
            "data" => $category->refresh(),
            "msg" => "Saved successfully"
        ];        

        return $output;
    }

    public function destroy($id){
        $output = [
            "code" => 0,
            "data" => null,
            "msg" => ""
        ];        

        $user = Auth::user();
        $category = Category::find($id);
        if(!$category){
            $output["msg"] = "No existe el elemento";
            return $output;
        }        

        if(!$user->can('eliminar-rubros')){
            $output["msg"] = "Operación denegada";
            return $output;
        }

        //Eliminando imagen 
        $img_path = null;
        if($category->img_presentation != 'default_img_category.png'){
            $img_path = 'files/categories/'.$category->img_presentation;
        }

        $category->delete();

        if($img_path != null && Storage::disk('local')->exists($img_path)){
            if(! Storage::disk('local')->delete($img_path) ){
                $output["msg"] = "No se pudo eliminar la imagen";
            }               
        }

        $output["code"] = 1;
        $output["msg"] = "Deleted Successfully";              

        return $output;
        
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
