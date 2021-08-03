<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PostEvent;
use App\DtlEvent;
use App\FilesOnPostEvents as FilesPost;
use App\postsEventsMeta;
use Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Validation\Rule;
use App\DtlEvent as Devs;

class PostEventController extends Controller
{
    public function __construct(){
		//$this->middleware('auth:api',['only'=>['store','findPostsPopular','switchStatePost','setPostPopular']]);
    }

    /**
    * Display table events 
    * @return \Illuminate\Http\Response
    */
    public function eventsTable(Request $request){

        $salida = [
            "code" => 0,
            "msg" => "",
            "data" => null
        ];

        /* Mas adenta se consultara si es necesario agregar la hora del evento, por ahora solo los muestra conforme 
        ** a la fecha que se ira a realizar   ----  Pero no sabra a que horas finaliza exactamente
        */

        date_default_timezone_set('America/El_Salvador');
        $range_init = date("Y-m-d")." 00:00:00"; //today
        $range_init = "2021-07-23 00:00:00"; //quitar este, es solo para pruebas 
        $range_end = date('Y-m-d', strtotime("+3 months", strtotime($range_init)))." 00:00:00";
        $limit = 3;

        $params = "call getEvents('$range_init','$range_end',$limit,@cn)";
        $items = DB::select($params);

        //crear pagination
        $salida["data"] = $items;

        return $salida;
    }

    //Filter for only manager roles and auth (Done)
    public function approval(Request $request){
        $salida = [
            "code" => 0,
            "msg" => "",
            "data" => null,
            'paginate' => null
        ];


        $per_page = ($request->per_page === null)?15:$request->per_page;
        //$result = PostEvent::where("status","review")->paginate($per_page);

        $result = DB::table("post_events AS e")
        ->join("users AS u","u.id","=","e.creator_id")
        ->leftJoin("dtl_events AS dtl","dtl.event_id","=","e.id")
        ->leftJoin("files_on_post_events AS f","f.id","=","e.presentation_img")
        ->select("e.id","e.title","e.content AS description", "e.type_post AS type",
        "f.name as presentation_img","e.is_popular","dtl.event_date","dtl.has_cost","dtl.cost","dtl.frequency","u.artistic_name AS nickname","u.id AS creator_id")->where("e.status","review")->paginate($per_page);

        $salida["paginate"] = [
            'total' =>$result->total(),
            'current_page'  => $result->currentPage(),
            'per_page'      => $result->perPage(),
            'last_page'     => $result->lastPage(),
            'from'          => $result->firstItem(),
            'to'            => $result->lastPage(),
        ];
        $salida["data"] = $result->items();
        $salida["code"] = 1;
        return $salida;
    }


    public function store(Request $request){
        $salida = [
            "code" => 0,
            "data" => null,
            "msg" => null
        ];

        $user = Auth::user();

        //validaciones de campos
        $validator = Validator::make($request->all(),[
            "post_type" => "required",
            "title" => "required",
            "description" => "required"
        ]);

        if($validator->fails()){
            $salida["msg"] = "Valores imcompletos";
            return $salida;
        };

        if($request->post_type == "event"){
            $validator = Validator::make($request->all(),[
                "event_price" => "required",
                "event_has_price" => "required",
                "frequency" => ["required",Rule::in(["unique","repeat"])],
                "event_date"  => ["required","date"]
            ]);
        }

        if($validator->fails()){
            $salida["msg"] = "Campos de evento imcompletos";
            return $salida;
        };

        DB::beginTransaction();

        $postEvent = new PostEvent();
        $id_img_presentation = 0;
        try{
            $postEvent->title = $request->title;
            $postEvent->content = $request->description;
            $postEvent->type_post = $request->post_type;
            $postEvent->creator_id = $user->id;
            $postEvent->save();

            $dtlEvent = new DtlEvent();
            if($request->post_type == "event"){
                $dtlEvent->event_date        = $request->event_date;
                $dtlEvent->frequency          = $request->frequency;
                $dtlEvent->has_cost             = $request->event_has_price;
                $dtlEvent->cost                     = $request->event_has_price == true ? $request->event_price : 0;
                $dtlEvent->event_id             = $postEvent->id;
                $dtlEvent->save();
            }

            //Realizar validacion de, tipo de archivos permitidos (por extension)
            
            //Si el usuario sube contenido
            if(isset($request->media)){

                $limite_carga = 70; //dejar a 70, agregar esto en la vista
                $files = $request->media;
                if(count($files) >= $limite_carga){
                    throw new \Exception("Límite de carga superado, máximo ".$limite_carga." archivos");
                }

                $numberfiles = 0;                
                while($numberfiles < count($files)){
                    $filesOnPostEvents = new FilesPost();
                    if (preg_match('/^data:image\/(\w+);base64,/', $files[$numberfiles]['data'])
                        ||
                        preg_match('/^data:application\/(\w+);base64,/', $files[$numberfiles]['data'])
                    ) {
                        $data = substr($files[$numberfiles]['data'], strpos($files[$numberfiles]['data'], ',') + 1);
                        $data = base64_decode($data);


                        
                        $extension = explode(".",$files[$numberfiles]['filename']);
                        //obtener el ultimo elemento del array creado (explode)
                        $extension = $extension[count($extension)-1];

                        //al parecer no es necesario 
                        date_default_timezone_set('America/El_Salvador');
                        $filename = uniqid()."_".time().".".$extension;
                        $pathname =  $files[$numberfiles]['type'] == "image" ? "files/images/" : "files/pdfs/" ;

                        $filesOnPostEvents->id_post_event      = $postEvent->id;
                        $filesOnPostEvents->name                   = $filename;
                        $filesOnPostEvents->type_file               = $files[$numberfiles]['type'];
                        $filesOnPostEvents->save();

                        if($id_img_presentation === 0 && $filesOnPostEvents->type_file === "image"){
                            $id_img_presentation = $filesOnPostEvents->id;
                        }

                        $path_store = $pathname.$filename;
                        Storage::disk('local')->put($path_store, $data);

                        //compresor de imagenes only image type 
                        if($filesOnPostEvents->type_file === "image"){
                        // resize the image to a width of 1000 and constrain aspect ratio (auto height)
                            $compress = Image::make(storage_path('app/' . $path_store));
                            $width      = $compress->width();
                            $height    = $compress->height();

                            if($width > 1500 || $height > 1500){
                                $compress->resize(1000, null,function($constraint) {
                                    $constraint->aspectRatio();
                                    // prevent possible upsizing
                                    $constraint->upsize();
                                    //en las pruebas upsize no evitaba por completo el incremento de peso para archivos pequeños
                                });
                                $compress->save(null,100);
                            }
                            $compress->destroy();
                        }

                    }else{
                        $filesOnPostEvents->id_post_event = $postEvent->id;
                        $filesOnPostEvents->name = $files[$numberfiles]['data'];
                        $filesOnPostEvents->type_file = $files[$numberfiles]['type'];
                        $filesOnPostEvents->save();
                        //Es un video
                        if($id_img_presentation === 0){
                            $id_img_presentation = $filesOnPostEvents->id;
                        }                        
                    }
                    $numberfiles++;
                }
            }

            if($id_img_presentation !== 0){
                $postEvent->presentation_img = $id_img_presentation;
                $postEvent->save();
            }

            $propietario = [];
            $propietario["id"] = $user->id;
            $propietario["name"] = $user->name;
            $propietario["nickname"] = $user->artistic_name;            
            $propietario["profile_img"] = $user->profile_img;

            DB::commit();

            $salida = [
                "code" => 1,
                "data" =>  [
                    "post" => $postEvent->load("media"),
                    "creator" => $propietario
                ],
                "msg" => "Request Complete"
            ];
        }catch(\Exception $e){
            DB::rollback();
            $salida['msg'] = "Error: " . $e->getMessage();
        }

        return $salida;        
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
        $query = "select pe.id,pe.title,pe.content,pe.type_post,pe.is_popular,fop.name,fop.type_file from post_events pe
        left join (select * from files_on_post_events fope where fope.type_file = 'image' group by fope.id_post_event) as fop on fop.id_post_event = pe.id
        where pe.is_popular = true";

        //Se busca por nombre y con campo popular = false  
        if(strlen($desc) > 0 && $popular == false){
            $query = "select pe.id,pe.title,pe.content,pe.type_post,pe.is_popular,fop.name,fop.type_file from post_events pe
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

		$post = PostEvent::with('media')
						->leftJoin('dtl_events','post_events.id','=', 'dtl_events.event_id')
						->join('users','users.id','=','post_events.creator_id')
						->leftJoin('media_profiles AS mp','mp.id','users.img_profile_id')
						->select('post_events.*','dtl_events.event_date','dtl_events.frequency','dtl_events.has_cost','dtl_events.cost',
						'dtl_events.frequency','mp.path_file AS creator_profile','users.name AS creator_name','users.artistic_name AS creator_nickname','users.id AS creator_id')
                        ->where("post_events.id",$id)->first();

        // $post = DB::table("post_events AS pe")
        // ->join("users AS u", "u.id","=","pe.creator_id")
        // ->leftJoin("media_profiles AS mp","u.img_profile_id","=","mp.id")
        // ->select("pe.id","pe.title","pe.content AS description","pe.type_post AS type","pe.creator_id","pe.is_popular",
        //     "pe.status","pe.created_at","u.name","u.artistic_name","mp.path_file AS img_owner")->where("pe.id",$id)->first();

        if($post == null){
            $salida["msg"] = "El elemento no existe";
            return $salida;
        };

        //$media = FilesPost::where('id_post_event',$id)->get();
        //$meta = postsEventsMeta::where('post_event_id',$id)->get();

        $salida = [
            "code" => 1,
            "data" => $post,
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
