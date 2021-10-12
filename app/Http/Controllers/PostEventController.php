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
use App\Popular;
use App\User;

class PostEventController extends Controller
{

    
    public function eventsTable(Request $request){

        $salida = [
            "code" => 0,
            "msg" => "",
            "data" => null
        ];

        //Si no se paso la variable la inicia con 1, sino hace la conversion del que trae 
        $page_aux = $request->page == null ? 1 : intval($request->page);
        //Si la conversion fue 0, es porque es el parseo no fue exitoso, se establece a uno 
        if($page_aux === 0){    $page_aux = 1;  }        
        $init_pagination = $request->init_pagination == null ? false : boolval($request->init_pagination);

        $paginate = [
            "total" => 0,
            "current_page" => 0,
            "per_page" => 0,
            "last_page" => 0,
            "from" => 0,
            "to" => 0
        ];    
        $paginate["per_page"] = 12;
        $paginate["current_page"] = $page_aux;
        $paginate["from"] = ( ($paginate["current_page"] * $paginate["per_page"]) - $paginate["per_page"]) +1;

        /* Mas adenta se consultara si es necesario agregar la hora del evento, por ahora solo los muestra conforme 
        ** a la fecha que se ira a realizar   ----  Pero no sabra a que horas finaliza exactamente
        */

        date_default_timezone_set('America/El_Salvador');
        $range_init = date("Y-m-d")." 00:00:00"; //today
        //$range_init = "2021-07-23 00:00:00"; //quitar este, es solo para pruebas 
        $range_end = date('Y-m-d', strtotime("+3 months", strtotime($range_init)))." 00:00:00";
        $items      = null;
        $offset     = $paginate["from"] - 1;
        $limit        = $paginate["per_page"];

        if($init_pagination){
            $call_string            = "call getEvents('$range_init','$range_end',0,0,true)";
            $result                    = DB::select($call_string);
            $paginate["total"]= intval($result[0]->calc_total);
        }

        $call_string            = "call getEvents('$range_init','$range_end',$limit,$offset,false)";
        $items                    = DB::select($call_string);


        //Segun la paginacion usada en todos los modulos, la variable "to" es la misma que la ultima pagina 
        $paginate["last_page"] = intval(ceil($paginate["total"] / $paginate["per_page"]));
        $paginate["to"] = $paginate["last_page"];         

        $salida["code"] = 1;
        $salida["data"] = $items;
        $salida["pagination"] = $paginate;
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


        $per_page = ($request->per_page == null)?15:$request->per_page;
        //$salida["extra"] = $per_page;
        //$result = PostEvent::where("status","review")->paginate($per_page);

        $result = DB::table("post_events AS e")
        ->join("users AS u","u.id","=","e.creator_id")
        ->leftJoin("dtl_events AS dtl","dtl.event_id","=","e.id")
        ->leftJoin("files_on_post_events AS f","f.id","=","e.presentation_img")
        ->select("e.id","e.title","e.content AS description", "e.type_post AS type",
        "f.name AS presentation_img","f.type_file AS presentation_type", "e.is_popular","dtl.event_date","dtl.has_cost","dtl.cost","dtl.frequency","u.artistic_name AS nickname",
        "u.id AS creator_id")->where("e.status","review")->paginate($per_page);

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

        //min:0 make sure the minimum value is 0 and no negative values are allowed. 
        //not_in:0 make sure value cannot be 0. So, combination of both of these rules does the job. 
        //required | numeric | min: 0 | not_in: 0

        if($request->post_type == "event"){
            $validator = Validator::make($request->all(),[
                "event_price" => "required | numeric | min: 0 ",
                "event_has_price" => "required",
                "frequency" => ["required",Rule::in(["unique","repeat"])],
                "event_date"  => ["required","date"]
            ]);
        }

        if($validator->fails()){
            $salida["msg"] = "Campos de evento no validos";
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

            //Guardando contador global de eventos o post para el usuario
            if($request->post_type == "event"){
                $global_items = intval($user->count_events);
                $global_items++;
                $user->count_events = $global_items;
            }else{
                $global_items = intval($user->count_posts);
                $global_items++;
                $user->count_posts = $global_items;
            }
            $user->save();
            
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

                        if($files[$numberfiles]['type'] == "image"){ //is a img
                            $filename = uniqid()."_".time().".".$extension;
                            $pathname = "files/images/";
                        }else if($files[$numberfiles]['type'] == "docfile"){ //is a document
                            $filename = $files[$numberfiles]['filename'];
                            $pathname = "files/docs/pe".$postEvent->id."/";
                        }

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
                        //Es un video
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
            $propietario["count_posts"] = $user->count_posts;
            $propietario["count_events"] = $user->count_events;

            DB::commit();
            $postEvent->refresh();
            $salida = [
                "code" => 1,
                "data" =>  [
                    "post" => $postEvent->load("media"),
                    "creator" => $propietario,
                    'dtl_event' => []
                ],
                "msg" => "Request Complete"
            ];
            //Agregar el detalle del evento 
            if($request->post_type == "event"){
                $salida["data"]["dtl_event"] = $dtlEvent;
            }
        }catch(\Exception $e){
            DB::rollback();
            $salida["msg"] = "Error en la operación, consulte soporte técnico.";
            //$salida['msg'] = "Error: " . $e->getMessage(); //for debugin
        }

        return $salida;        
    }

    public function update(Request $request, $id){
        $salida = [
            "code" => 0,
            "data" => null,
            "msg" => null,
            "extra" => ""
        ];


        $user = Auth::user();
        
        $postEvent = PostEvent::find($id);
        if(!$postEvent){
            $salida["msg"] = "No existe el elemento";
            return;
        }

        //Verificando permisos 
        if(!$user->can('editar-publicaciones') && $postEvent->creator_id != $user->id){
            $salida["msg"] = "Operación denegada";
            return $salida;
        }

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
        $id_img_presentation = 0;
        try{
            $postEvent->title = $request->title;
            $postEvent->content = $request->description;
            $postEvent->type_post = $request->post_type;
            //Siempre que edite es necesario pasar por revision 
            $postEvent->status = "review";
            $postEvent->save();

            if($postEvent->type_post == "event"){
                $dtlEvent = DtlEvent::where("event_id","=",$postEvent->id)->first();
                if(!$dtlEvent){
                    throw new \Exception("Detalle de evento no encontrado");
                }
                $dtlEvent->event_date        = $request->event_date;
                $dtlEvent->frequency          = $request->frequency;
                $dtlEvent->has_cost             = $request->event_has_price;
                $dtlEvent->cost                     = $request->event_has_price == true ? $request->event_price : 0;
                $dtlEvent->event_id             = $postEvent->id;
                $dtlEvent->save();                
            }
            
            //ELIMINACION DE CONTENIDO EXISTENTE 
            $count_drop = 0;
            if($request->mediadrop_ids){
                foreach($postEvent->media as $drope){//por cada medio dentro de la publicacion 
                    foreach($request->mediadrop_ids as $dropid){
                        //aqui aplicacar una verificacion para los pdf ya que se guardan dentro de folders 
                        if($drope->id === $dropid ){
                            $aux_path = "";
                            switch($drope->type_file){
                                case 'image': $aux_path = "files/images/".$drope->name;break;
                                case 'docfile': $aux_path = "files/docs/pe".$postEvent->id."/".$drope->name;break; //aplicar filtro, solo agregar id de post 
                            }
                            //para video se omite y solo se borra el modelo 
                            if(trim($aux_path) !== "" && Storage::disk('local')->exists($aux_path)){
                                if(! Storage::disk('local')->delete($aux_path) ){
                                    throw new \Exception("No se logró eliminar algunos medios".$drope->name);
                                }   
                            }
                            if(!$drope->delete()){
                                throw new \Exception("No se logró eliminar el registro");
                            }
                            $count_drop++;
                            break;//primer form 
                        }
                    }

                    if($count_drop == count($request->mediadrop_ids)){
                        $salida["extra"] = "Se eliminaron " .$count_drop . " Elementos";
                        break; //break 2 for, all element was deleted 
                    }
                }
            }

            //$salida["extra"] = "Tipos: ";
            //CARGA DE CONTENIDO NUEVO 
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

                        $salida["extra"] .= $files[$numberfiles]['type'] . " / ";
                        //al parecer no es necesario 
                        date_default_timezone_set('America/El_Salvador');
                        if($files[$numberfiles]['type'] == "image"){ //is a img
                            $filename = uniqid()."_".time().".".$extension;
                            $pathname = "files/images/";
                        }else if($files[$numberfiles]['type'] == "docfile"){ //is a document 
                            $filename = $files[$numberfiles]['filename'];
                            $pathname = "files/docs/pe".$postEvent->id."/";
                        }


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
                        //Es un video
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

            //Si se elimino la imagen de presentacion que hacer ? 
            if($id_img_presentation !== 0){
                $postEvent->presentation_img = $id_img_presentation;
                $postEvent->save();
            }            

            $postEvent->owner->id;
            $propietario = [];
            $propietario["id"] = $postEvent->owner->id;
            $propietario["name"] = $postEvent->owner->name;
            $propietario["nickname"] = $postEvent->owner->artistic_name;
            $propietario["profile_img"] = $postEvent->owner->profile_img;

            DB::commit();
            //Importante dejar en esta posicion, dato que en las lineas de arriba se carga el owner, se debe ocultar antes de 
            //enviar, solo se debe enviar los medios/archivos ( load('media') )
            $postEvent->makeHidden('owner');
            $salida = [
                "code" => 1,
                "data" =>  [
                    "post" => $postEvent->load("media"),
                    "creator" => $propietario,
                    'dtl_event' => []
                ],
                "msg" => "Request Complete"
            ];

            //Agregar el detalle del evento 
            if($request->post_type == "event"){
                $salida["data"]["dtl_event"] = $dtlEvent;
            }            
        }catch(\Throwable $ex){
            DB::rollback();
            $salida["msg"] = "Error al actualizar publicacion";
            //$salida["msg"] = "Error al actualizar publicacion ".$ex->getMessage(); //for debug
        }

        return $salida;
    }

    public function destroy($id){
        $salida = [
            "code" => 0,
            "data" => null,
            "msg" => ""
        ];


        $user = Auth::user();
        $postEvent = PostEvent::find($id);
        if(!$postEvent){
            $salida["msg"] = "No existe el elemento";
            return;
        }

        //Verificando permisos 
        if(!$user->can('eliminar-publicaciones') && $postEvent->creator_id != $user->id){
            $salida["msg"] = "Operación denegada";
            return $salida;
        }

        $creator = User::find($postEvent->creator_id);
        if(!$creator){
            $salida["msg"] = "Inconsistencia de datos";
            return;
        }



        try{
            //eliminando archivos multimedia 
            foreach($postEvent->media as $del){
                $aux_path = "";
                switch($del->type_file){
                    case 'image': $aux_path = "files/images/".$del->name;break;
                    case 'docfile': $aux_path = "files/docs/pe".$postEvent->id."/".$del->name;break; //aplicar filtro, solo agregar id de post 
                }
                //Si no esta es porque es video, solo se elimina mas abajo 
                if(trim($aux_path) !== "" && Storage::disk('local')->exists($aux_path)){
                    if(! Storage::disk('local')->delete($aux_path) ){
                        throw new \Exception("No se logró eliminar algunos medios".$drope->name);
                    }   
                }                
                //Por defecto retorna una exception 
                $del->delete();
            }
            //Por defecto retorna una exception 
            $postEvent->delete();
            if($postEvent->post_type == "event"){
                $global_items = intval($creator->count_events);
                $global_items--;
                $global_items = ($global_items < 0) ? 0 : $global_items;
                $creator->count_events = $global_items;
            }else{
                $global_items = intval($creator->count_posts);
                $global_items--;
                $global_items = ($global_items < 0) ? 0 : $global_items;
                $creator->count_posts = $global_items;
            }            
            $creator->save();
            $salida["code"] = 1;
            $salida["msg"] = "Deleted Successfully";
        }catch(\Throwable $ex){
            $salida["msg"] = "Error al eliminar publicacion";
            //$salida["msg"] = "Error al actualizar publicacion ".$ex->getMessage(); //for debug
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

    //Uscar esta en lugar de la de arriba, y se elimina la de arriba 
    public function popularItems(){
        $salida = [
            "code" => 0,
            "msg" =>"",
            "data" => []
        ];

        #Se envian todas, para el administrador se muestran todas, para la pagina princila
        #Se filtra en la vista solo los aprovados 
        $result = DB::table("post_events AS e")
        ->join("users AS u","u.id","=","e.creator_id")
        ->leftJoin("dtl_events AS dtl","dtl.event_id","=","e.id")
        ->leftJoin("files_on_post_events AS f","f.id","=","e.presentation_img")
        ->select("e.id","e.title","e.content AS description", "e.type_post AS type","e.status",
        "f.name AS presentation_img","f.type_file AS presentation_type", "e.is_popular","dtl.event_date","dtl.has_cost","dtl.cost","dtl.frequency","u.artistic_name AS nickname",
        "u.id AS creator_id")->where("e.is_popular","=",true)->get();
        $salida["code"] = 1;
        $salida["data"] = $result;
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

        //Mostrar solo si el usuario estado logeado, y si el usuario logeado es el creado 
        //O si el usuario logeado es un administrador 

        if($post == null){
            $salida["msg"] = "El elemento no existe";
            return $salida;
        };



        if($post->status == "review"){//mostrar solo para admin o propietario 
            if(Auth::guard('web')->check()){//usuario logeado 
                //Usuariologeado es un invitado que no es el creado del post 
                if(Auth::user()->hasRole('Invitado') && Auth::user()->id != $post->creator_id){
                    $salida["msg"] = "El elemento no ha sido aprobado";
                    return $salida;                    
                }
            }else{ //Usuairo no logeado 
                $salida["msg"] = "El elemento no ha sido aprobado no";
                return $salida;
            }
        }

        //Si el elemento esta aprovado enviar 
        $salida["code"]         = 1;
        $salida["data"]         = $post;
        $salida["msg"]          = "Processed Successfully";

        return $salida;
    }


    public function switchStatePost(Request $request){
        $salida = [
            "code" => 0,
            "data" => null,
            "msg" => null
        ];
        
        if(!Auth::user()->can('aprobar-publicaciones')){
            $salida["msg"] = "Operación denegada";
            return $salida;            
        }

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

        $post = PostEvent::find($id);
        if(!$post){
            $salida["msg"] = "No existe el recurso";
            return $salida;
        }

        $post->status = $new_state;
        if(!$post->save()){
            $salida["msg"] = "Se produjo un error al cambiar el estado";
            return $salida;
        }

        $salida = [
            "code" => 1,
            "data" => ["new_state" => $new_state],
            "msg" => "Operation Successfully"
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

        if(!Auth::user()->can('destacar-publicaciones')){
            $salida["msg"] = "Operación denegada";
            return $salida;            
        }


        $id = $request->id;
        $new_state = $request->new_state;

        $count = Popular::count();
        if($count >= 6 && $new_state == true){
            $salida["msg"] = "No se pudo agregar: solo 6 Elementos destacados como maximo";
            return $salida;            
        }

        $post = PostEvent::find($id);
        if(!$post){
            $salida["msg"] = "No existe el recurso";
            return $salida;                     
        }

        DB::beginTransaction();
        try{
            $post->is_popular = $new_state;
            $top = Popular::where("post_event_id",$post->id)->first();

            //Sino existe en la tabla de popular y la accion actual es agregarlo
            if(!$top && $post->is_popular == true){ //Agregando 
                    $top = new Popular();
                    $top->post_event_id = $post->id;
                    $top->save();
            }

            //Si existe en la tabla y la accion es quitar de destacados 
            if($top && $post->is_popular == false){
                $top->delete();
            }     

            //Para los otros dos casos, se omite porque en los dos casos restantes no se haria nada 
            $post->save();
            $salida["code"] = 1;
            $salida["data"] =  ["new_state" => $new_state];
            $salida["msg"] = "Process Successfully";            
            DB::commit();
        }catch(\Throwable $ex){
            DB::rollback();
            $salida["msg"] = "Error en la operación, consulte soporte técnico.";
            //$salida['msg'] = "Error: " . $ex->getMessage(); //for debugin            
        }
        return $salida;
    }
}
