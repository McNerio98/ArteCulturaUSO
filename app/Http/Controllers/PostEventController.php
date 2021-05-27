<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PostEvent;
use App\FilesOnPostEvents as FilesPost;
use App\postsEventsMeta;
use Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class PostEventController extends Controller
{
    public function __construct(){
		$this->middleware('auth:api',['only'=>['store','findPostsPopular','switchStatePost','setPostPopular']]);
    }

    public function store(Request $request){
        $salida = [
            "code" => 0,
            "data" => null,
            "msg" => null
        ];

        //permisos
        //Se esta usando el middleware api entonces buscara el valor del api_token  
        $user = Auth::user();

        //validaciones de campos
        $validator = Validator::make($request->all(),[
            "post_type" => "required",
            "title" => "required",
            "description" => "required"
        ]);

        if($validator->fails()){
            $salida["msg"] = "Valores imcompletos linea 36";
            return $salida;
        };

        if($request->post_type == "event"){
            $validator = Validator::make($request->all(),[
                "event_price" => "required",
                "event_category" => "required",
                "event_type" => "required",
                "event_date"  => "required"
            ]);
        }

        if($validator->fails()){
            $salida["msg"] = "Valores imcompletos linea 50";
            return $salida;
        };

        DB::beginTransaction();

        $postEvent = new PostEvent();
        try{
            $postEvent->title = $request->title;
            $postEvent->content = $request->description;
            $postEvent->type_post = $request->post_type;
            $postEvent->creator_id = $user->id;
            $postEvent->save();

            if($request->post_type == "event"){
                //Se llenan los datos para un evento
                $keys = ["event_date","event_price","event_type","event_category"];
                foreach ($keys as $key => $value) {
                    $option = new postsEventsMeta();
                    $option->post_event_id = $postEvent->id;
                    $option->key = $value;
                    $option->value= $request->{$value};
                    $option->save();
                }

            }

            //Si el usuario sube contenido
            if(isset($request->media)){
                $files = $request->media;
                $numberfiles = 0;
                while($numberfiles < count($files)){
                    $filesOnPostEvents = new FilesPost();
                    if (preg_match('/^data:image\/(\w+);base64,/', $files[$numberfiles]['data'])
                        ||
                        preg_match('/^data:application\/(\w+);base64,/', $files[$numberfiles]['data'])
                    ) {
                        $data = substr($files[$numberfiles]['data'], strpos($files[$numberfiles]['data'], ',') + 1);

                        $data = base64_decode($data);

                        //$filename = uniqid() . $files[$numberfiles]['filename'];
                        //$pathname = $files[$numberfiles]['type'] == "image" ? ("files/images/" . $filename) : ("files/pdfs/" . $filename);
                        
                        $extension = explode(".",$files[$numberfiles]['filename']);
                        $extension = $extension[count($extension)-1];

                        $e = microtime();
                        $string_date = explode(" ",$e);
                        $filename = uniqid() . $string_date[1].".".$extension;
                        $pathname =  $files[$numberfiles]['type'] == "image" ? "files/images/" : "files/pdfs/" ;

                        $filesOnPostEvents->id_post_event = $postEvent->id;
                        $filesOnPostEvents->name = $filename;
                        $filesOnPostEvents->path_file = $pathname;
                        $filesOnPostEvents->type_file = $files[$numberfiles]['type'];
                        $filesOnPostEvents->save();

                        $path_store = $pathname.$filename;
                        Storage::disk('local')->put($path_store, $data);

                    }else{
                        $filesOnPostEvents->id_post_event = $postEvent->id;
                        $filesOnPostEvents->name = "Youtube Video";
                        $filesOnPostEvents->path_file = $files[$numberfiles]['data'];
                        $filesOnPostEvents->type_file = $files[$numberfiles]['type'];
                        $filesOnPostEvents->save();
                    }
                    $numberfiles++;
                }
            }
            DB::commit();
            $salida = [
                "code" => 1,
                "data" =>  $postEvent->load("media"),
                "msg" => "Ok MC ".$postEvent->id
            ];
        }catch(\Exception $e){
            DB::rollback();
            $salida['msg'] = "Error: " . $e;
        }

        return $salida;        
        //$header = $request->header('Authorization');
    }

    public function popularPost(){
        $salida = [
            "code" => 0,
            "msg" =>"",
            "data" => []
        ];

        $query = "select pe.id,pe.title,concat(substring(pe.content,1,100),'...') as content,pe.type_post,pe.is_popular,fop.name,fop.path_file,fop.type_file from post_events pe
        left join (select * from files_on_post_events fope where fope.type_file = 'image' group by fope.id_post_event) as fop on fop.id_post_event = pe.id
        where pe.is_popular = true"; 

        $result = DB::select(DB::raw($query));
        $salida = [
            "code" => 1,
            "msg" =>"result ok",
            "data" => $result
        ];

        return $salida;        
    }


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
        $query = "select pe.id,pe.title,pe.content,pe.type_post,pe.is_popular,fop.name,fop.path_file,fop.type_file from post_events pe
        left join (select * from files_on_post_events fope where fope.type_file = 'image' group by fope.id_post_event) as fop on fop.id_post_event = pe.id
        where pe.is_popular = true";

        //Se busca por nombre y con campo popular = false  
        if(strlen($desc) > 0 && $popular == false){
            $query = "select pe.id,pe.title,pe.content,pe.type_post,pe.is_popular,fop.name,fop.path_file,fop.type_file from post_events pe
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

        $post = DB::table("post_events AS pe")
        ->join("users AS u", "u.id","=","pe.creator_id")
        ->select("pe.id","pe.title","pe.content AS description","pe.type_post AS type","pe.creator_id","pe.is_popular",
            "pe.status","pe.created_at","u.name","u.artistic_name")->where("pe.id",$id)->first();

        if($post == null){
            $salida["msg"] = "El elemento no existe";
            return $salida;
        };

        $media = FilesPost::where('id_post_event',$id)->get();
        $meta = postsEventsMeta::where('post_event_id',$id)->get();

        $info = [
            "post" => $post,
            "media" => $media,
            "meta" => $meta
        ];

        $salida = [
            "code" => 1,
            "data" => $info,
            "msg" => "result ok"
        ];

        return $salida;
    }


    public function switchStatePost(Request $request){
        $salida = [
            "code" => 0,
            "data" => null,
            "msg" => null
        ];
        
        if(! isset($request->id, $request->new_state)){
            $salida['msg'] = "Valores imcompletos, Recargue la pagina";
            return $salida;
        }

        $id = $request->id;
        $new_state = $request->new_state;
        
        $valid_range = array('review','approved','delete');
        if(! in_array($new_state,$valid_range)){
            $salida['msg'] = "Valores imcompletos, Recargue la pagina";
            return $salida;        
        }

        //updating 
        $rows_affected = PostEvent::where('id',$id)->update(['status'=>$new_state]);
        
        if($rows_affected < 1){
            $salida["msg"] = "Se produjo un error al cambiar el estado";
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
    public function setPostPopular(Request $request)
    {
        $salida = [
            "code" => 0,
            "data" => null,
            "msg" => null
        ];

        if(! isset($request->id, $request->new_state)){
            $salida['msg'] = "Valores imcompletos, Recargue la pagina";
            return $salida;
        }

        $id = $request->id;
        $new_state = $request->new_state;
        
        $count = PostEvent::where('is_popular','=',true)->count();

        if($count >= 6 && $new_state == true){
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
}
