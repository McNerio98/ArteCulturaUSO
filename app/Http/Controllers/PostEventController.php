<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PostEvent;
use App\FilesOnPostEvents as FilesPost;
use Illuminate\Support\Facades\DB;

class PostEventController extends Controller
{

    public function findPostsPopular(Request $request)
    {
        $salida = [
            "code" => 0,
            "msg" =>"",
            "data" => []
        ];

        if(! isset($request->popular)){
            $salida['msg'] = "Valores imcompletos, Recargue la pagina";
            return $salida;
        }

        $desc = $request->desc;
        $popular = $request->popular;

        //Todos los post establecidos como popular
        $query = "select pe.id,pe.title,pe.content,pe.type_post,pe.is_popular,fop.path_file,fop.type_file from post_events pe
        left join (select * from files_on_post_events fope where fope.type_file = 'image' group by fope.id_post_event) as fop on fop.id_post_event = pe.id
        where pe.is_popular = true";

        //Se busca por nombre y con campo popular = false  
        if(strlen($desc) > 0 && $popular == false){
            $query = "select pe.id,pe.title,pe.content,pe.type_post,pe.is_popular,fop.path_file,fop.type_file from post_events pe
            left join (select * from files_on_post_events fope where fope.type_file = 'image' group by fope.id_post_event) as fop on fop.id_post_event = pe.id
            where pe.is_popular = false and pe.title like '%$desc%' limit 6";
        }
        $result = DB::select(DB::raw($query));
        $salida = [
            "code" => 1,
            "msg" =>"result ok",
            "data" => $result
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
        $salida = [
            "code" => 0,
            "data" => null,
            "msg" => null
        ];

        if(! isset($id)){
            $salida['msg'] = "Valores imcompletos, Recargue la pagina";
            return $salida;
        }


        $post = PostEvent::find($id);
        $media = FilesPost::where('id_post_event',$id)->get();

        $info = [
            "info" => $post,
            "media" => $media
        ];


        $salida = [
            "code" => 1,
            "data" => $info,
            "msg" => "result ok"
        ];

        return $salida;
    }

    /**
    * Set a post as popular post, before the popular post limit is verified, by default it is 6  maximun 
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    public function setPostPopular($id,$new_state)
    {
        if(! isset($id, $new_state)){
            $salida['msg'] = "Valores imcompletos, Recargue la pagina";
            return $salida;
        }

        if(!($new_state == 0 || $new_state == 1)){
            $salida['msg'] = "Valores no validos";
            return $salida;
        }

        $salida = [
            "code" => 0,
            "data" => null,
            "msg" => null
        ];
        
        $count = PostEvent::where('is_popular','=',intval(true))->count();

        if($count > 6 || $current_state = 0){
            $salida["msg"] = "No se pudo agregar: solo 6 Elementos destacados como maximo";
            return $salida;
        }

        //actualizando 
        $rows_affected = PostEvent::where('id',$id)->update(['is_popular'=>$new_state]);

        if($rows_affected < 1){
            $salida["msg"] = "Se produjo un error al establecer como contenido destacado";
            return $salida;
        }

        $salida = [
            "code" => 1,
            "data" => ["new_state" => $new_state],
            "msg" => "result Ok"
        ];

        return $salida;
    }


        /**
    * Set a post as popular post, before the popular post limit is verified, by default it is 6  maximun 
    * @param int $id
    * @return \Illuminate\Http\Response
    */

    public function postsPopular(){
        $salida = [
            "code" => 0,
            "data" => null,
            "msg" => ""
        ];

        $query = "select pe.id,pe.title,pe.content,pe.type_post,pe.is_popular,fop.path_file,fop.type_file from post_events pe
        left join (select * from files_on_post_events fope where fope.type_file = 'image' group by fope.id_post_event) as fop on fop.id_post_event = pe.id
        where pe.is_popular = true";
        
        $result = DB::select(DB::raw($query));


        $salida = [
            "code" => 1,
            "data" => $result,
            "msg" => "result Ok"
        ]; 
        
        return $salida;
    }

}
