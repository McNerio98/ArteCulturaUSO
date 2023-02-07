<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Tag;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;

class TagsController extends Controller
{

    //Already has filter protection (Auth only)
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
            "msg" => "Request complete"
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

        if(!Auth::user()->can('crear-rubros')){
            $salida["msg"] = "Operación denegada";
            return $salida;            
        }

        //min:0 make sure the minimum value is 0 and no negative values are allowed. not_in:0 make sure value cannot be 0
        $validator = Validator::make($request->all(),[
            "tag_name" => "required|min:2|max:50",
            "category_id" =>"required|numeric|min:0|not_in:0"
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

        if(!Auth::user()->can('editar-rubros')){
            $salida["msg"] = "Operación denegada";
            return $salida;            
        }

        if($id == null || $id == 0){
            $salida["msg"] = "Parametros imcompletos";
            return $salida;
        }

        $validator = Validator::make($request->all(),[
            "tag_name" => "required|min:2|max:50",
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
            "msg" => "Updated successfully"
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
        $output = [
            "code" => 0,
            "data" => null,
            "msg" => null,
            "extra" => ""
        ];
        if(! isset($id)){
            $output['msg'] = "Valores imcompletos";
            return $output;
        }

        $tag = Tag::find($id);
        $userIDs = [];
        
        DB::beginTransaction();
        try{
            //Obtener todos los usuarios aginados a ese tag para poder hacer una restructuracion 
            $userList = User::select('users.id')->where('top.tag_id',$id)
                ->join('tags_on_profiles AS top','top.user_id','users.id')
                ->get();
            
            if(count($userList) > 100){
                throw new \Exception("Operacion bloqueda, excede los 100 usuarios");
            }


            foreach($userList as $user){
                array_push($userIDs,$user->id);
            }

            if(! $tag->delete()){
                throw new \Exception("Error al eliminar la etiqueta");
            }          
            
            $mod = User::find($userIDs);
            foreach($mod as $e){
                $e->preRefreshTags();
                $e->saveOrFail();
            }


            $output["code"] = 1;
            $output["msg"] = "Completed";
            $output["data"] = $tag;
            DB::commit();
        }catch(\Exception $ex){
            DB::rollback();
            //$output['msg'] = "Error: " . $ex->getMessage();
            $output["msg"] = "Error en la operación, consulte soporte técnico.";
        }

        return $output;
    }
}
