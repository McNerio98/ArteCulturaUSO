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
use Illuminate\Support\Facades\Http;
use App\ConfigOption;

class PostEventController extends Controller
{


    public function geodecoding(){
        $output = [
            "code" => 0,
            "msg" => "",
            "data" => null
        ];

        $API_KEY = ConfigOption::getOption("API_KEY_GEODEC");
        if(is_null($KEY_API)){
            $output["msg"] = "Ups! Hemos tenido un problema, inténtalo más tarde";
            return $output;
        }
        $directionSearch = $request->direction_search;
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=${directionSearch}&key=${API_KEY}";    

        $response = Http::get($url);
        $output["code"] = 1;
        $output["data"] = $response->json();
        $output["msg"] = "Consulta completada!";
        return $output;
    }
    
    /**
     * La llamada al API se creo aqui porque en el JS no es soportado, solo desde su misma libreria
     */
    public function places(Request $request){
        $output = [
            "code" => 0,
            "msg" => "",
            "data" => null
        ];

        $API_KEY = ConfigOption::getOption("API_KEY_PLACES");
        if(is_null($API_KEY)){
            $output["msg"] = "Ups! Hemos tenido un problema, inténtalo más tarde";
            return $output;
        }        
        $placeSearch = $request->place_search;
        $url = "https://maps.googleapis.com/maps/api/place/findplacefromtext/json";
        $url .= "?fields=formatted_address%2Cname%2Cgeometry&input=$placeSearch&inputtype=textquery&key=$API_KEY";

        $response = Http::get($url);
        $output["code"] = 1;
        $output["data"] = $response->json();
        $output["msg"] = "Consulta completada!";
        return $output;
    }

    
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


    public function nearby(Request $request){
        $output = [
            "code" => 0,
            "data" => null,
            "msg" => null
        ];

		$result = PostEvent::where('dtl.event_date','>=',date('Y-m-d h:i:s'))
            ->where('dtl.is_geo',true)
            ->join('dtl_events AS dtl','dtl.event_id','post_events.id')
			->with('media')
			->with('owner')
			->with('event_detail')
            ->get();
		$items = [];
		foreach($result as $el){
			$el->owner->load('profile_img');
			$items[] = $el;
		}        

        $output["code"] = 1;
        $output["data"] = $items;
        $output["msg"] = "Elementos recuperados";
        
        return $output;
    }


    public function upsert(Request $request){
        $output = [
            "code" => 0,
            "data" => null,
            "msg" => null
        ];

        $user = Auth::user();

        $params = [
            "post.title" => "required",
            "post.description" => "required",
            "post.type" => "required",
            "post.is_popular" => "required",
            "post.status" => "required",
        ];

        //min:0 make sure the minimum value is 0 and no negative values are allowed. 
        //not_in:0 make sure value cannot be 0. So, combination of both of these rules does the job. 
        //required | numeric | min: 0 | not_in: 0
        if($request->post["type"] == "event"){
            $params["dtl_event.event_date"] = "required | date";
            $params["dtl_event.has_cost"] = "required";
            $params["dtl_event.cost"] = "required | numeric | min: 0 ";
            $params["dtl_event.frequency"] = ["required",Rule::in(["unique","repeat"])];
        }

        $validator = Validator::make($request->all(),$params);
        if($validator->fails()){
            $output["msg"] = "Valores imcompletos";
            return $output;
        };

        if($request->post["id"] == 0){
            $postEvent = new PostEvent();
        }else{
            $postEvent = PostEvent::find($request->post["id"]);
        }

        //For update only 
        if(!$postEvent){
            $output["msg"] = "Ítem no encontrado";
            return $output;            
        }        


        DB::beginTransaction();
        $idImgPresentation = 0;

        try{
            $postEvent->title                  = $request->post["title"];
            $postEvent->content          = $request->post["description"];
            $postEvent->status              = 'approved';
            $postEvent->type_post       = $request->post["type"];
            $postEvent->creator_id      = $user->id;
            $postEvent->save();

            if($request->post["type"] == "event"){
                if($request->post["id"] != 0){
                    $dtlEvent   = $postEvent->event_detail;
                }else{
                    $dtlEvent = new DtlEvent();
                }

                if(is_null($dtlEvent)){
                    throw new \Exception("Error al verificar detalle de evento, reporte error.");
                }
               
                $dtlEvent->event_date        = $request->dtl_event["event_date"];
                $dtlEvent->frequency          = $request->dtl_event["frequency"];
                $dtlEvent->has_cost             = $request->dtl_event["has_cost"];
                $dtlEvent->cost                     = $request->dtl_event["has_cost"] == true ? $request->dtl_event["cost"] : 0;
                $dtlEvent->municipio_id     = $request->dtl_event["address"]["municipio_id"];
                $dtlEvent->address              = $request->dtl_event["address"]["details"];
                $dtlEvent->geo_lat              = $request->dtl_event["geo"]["lat"];
                $dtlEvent->get_lng              = $request->dtl_event["geo"]["lng"];
                $dtlEvent->is_geo                = $request->dtl_event["is_geo"];
                $dtlEvent->event_id             = $postEvent->id;
                $dtlEvent->save();
            }

            //Guardando contador global de eventos o post para el usuario
            if($request->post["type"] == "event"){
                $global_items = intval($user->count_events);
                $global_items++;
                $user->count_events = $global_items;
            }else{
                $global_items = intval($user->count_posts);
                $global_items++;
                $user->count_posts = $global_items;
            }
            
            $user->save();

            #Carga de archivos 
            $limite_carga = 10;
            $index = 0;
            $files = $request->media;
            $mediadrop_ids = $request->mediadrop_ids;
            if(count($files) >= ($limite_carga - count($mediadrop_ids))){
                throw new \Exception("Límite de carga superado, máximo ".$limite_carga." archivos");
            }            

            #Agregar contenido nuevo (todo los que tengan id = 0)
            while($index < count($files)){
                if($files[$index]["id"] == 0){
                    $filesOnPostEvents = new FilesPost();
                    if($files[$index]["type_file"] == "image" || $files[$index]["type_file"] == "docfile"){
                        $dataFile = substr($files[$index]['data'], strpos($files[$index]['data'], ',') + 1);
                        $dataFile = base64_decode($dataFile);                       
                        $extensionFile = explode(".",$files[$index]['name']);               
                        $extensionFile = $extensionFile[count($extensionFile)-1];             
                        
                        if($files[$index]['type_file'] == "image"){ //is a img
                            $filename = "pe".uniqid()."_".time().".".$extensionFile;
                            $pathname = "files/images/";
                        }else if($files[$index]['type_file'] == "docfile"){ //is a document
                            $filename = $files[$index]['name'];
                            //Crear folder especifico para no remplazar de otra publicacion 
                            $pathname = "files/docs/pe".$postEvent->id."/";
                        }                 
                        
                        $filesOnPostEvents->id_post_event      = $postEvent->id;
                        $filesOnPostEvents->name                   = $filename;
                        $filesOnPostEvents->type_file               = $files[$index]['type_file'];
                        $filesOnPostEvents->save();            

                        $path_store = $pathname.$filename;
                        Storage::disk('local')->put($path_store, $dataFile);                                            
                        
                        //Nueva imagen de presentacion 
                        /**Aqui hacer validacion para verificar que imagen de presentacion no balla dentro de los eliminados */
                        if($filesOnPostEvents->type_file === "image" && $idImgPresentation == 0){
                            $idImgPresentation = $filesOnPostEvents->id;
                        }

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
                    }

                    if($files[$index]["type_file"] == "video"){
                        $filesOnPostEvents->id_post_event      = $postEvent->id;
                        $filesOnPostEvents->name               = $files[$index]['name'];
                        $filesOnPostEvents->type_file          = $files[$index]["type_file"];
                        $filesOnPostEvents->save();                    
                    }                    
                }

                $index++;
            }

            //Aqui deveria continuar

            #Creando auxiliar porque el de la solicitud no es modificable (Da error)
            #Eliminacion de multimedias existentes para caso de actualización
            #En la eliminacion se puede incluir eliminacion de imagen de presentacion por nueva actualización             
            $mediadrop_ids = $request->mediadrop_ids;
            #Se actualiza por una nueva imagen
            if($idImgPresentation !== 0){
                /**Verificando si tenia una anteriormente en caso de actualizacion, se agrega para eliminar */
                if(!is_null($postEvent->presentation_img) && $postEvent->presentation_img != 0){
                    array_push($mediadrop_ids,$postEvent->presentation_img);
                }
                $postEvent->presentation_img = $idImgPresentation;
                $postEvent->save();
            }else{
                #/Si no se cargo nueva imagen de presentacion, se verifica si dentro de las eliminaciones esta la 
                #imagen de presentacion, si es asi se setea a nula el post actual 
                if(in_array($postEvent->presentation_img,$mediadrop_ids)){
                    $postEvent->presentation_img = null;
                    $postEvent->save();
                }
            }
        

            $count_drop = 0;
            if($request->post["id"] != 0 && count($mediadrop_ids) > 0){
                foreach($postEvent->media as $candidato){
                    foreach($mediadrop_ids as $dropid){
                        if($candidato->id === intval($dropid)){
                            $aux_path = "";
                            #Para video se omite eliminacion de storage y se borra el modelo solamente 
                            switch($candidato->type_file){
                                case 'image': {$aux_path = "files/images/".$candidato->name;break;}
                                case 'docfile': {$aux_path = "files/docs/pe".$candidato->id."/".$candidato->name;break;}
                            }

                            if(trim($aux_path) !== "" && Storage::disk('local')->exists($aux_path)){
                                if(! Storage::disk('local')->delete($aux_path) ){
                                    throw new \Exception("No se logró eliminar algunos medios".$candidato->name);
                                }                                   
                            }

                            if(!$candidato->delete()){
                                throw new \Exception("No se logró eliminar el registro");
                            }
                            $count_drop++;                            
                            #Romper primer bucle 
                            break;
                        }
                    }

                    if($count_drop == count($mediadrop_ids)){
                        $salida["extra"] = "Se eliminaron " .$count_drop . " Elementos";
                        break; //break 2 for, all element was deleted 
                    }
                }              
            }                        
            
            DB::commit();
            #El detalle de evento siempre se carga aunq sea solo post (lo carga como null)
            $postEvent->refresh();
            $postEvent->load('owner');
            $postEvent->owner->load('profile_img');
            $postEvent->load('media');
            $postEvent->load('event_detail');

            if($request->post["id"] == 0){
                $output["msg"] = "Elemento creado";
            }else{
                $output["msg"] = "Elemento actualizado";
            }

            $output["code"] = 1;
            $output["data"] = $postEvent;            
        }catch(\Exception $e){
            DB::rollback();
            //$output["msg"] = "Error en la operación, consulte soporte técnico.";
            $output['msg'] = "Error: " . $e->getMessage(); //for debugin
        }

        return $output;        
    }

    public function destroy($id){
        $output = [
            "code" => 0,
            "data" => null,
            "msg" => ""
        ];


        $user = Auth::user();
        $postEvent = PostEvent::find($id);
        if(!$postEvent){
            $output["msg"] = "No existe el elemento";
            return;
        }

        //Verificando permisos 
        if(!$user->can('eliminar-publicaciones') && $postEvent->creator_id != $user->id){
            $output["msg"] = "Operación denegada";
            return $output;
        }

        $creator = User::find($postEvent->creator_id);
        if(!$creator){
            $output["msg"] = "Inconsistencia de datos";
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

    

    public function find($id)
    {
        $output = [
            "code" => 0,
            "data" => null,
            "msg" => null
        ];

        if(! isset($id)){
            $output['msg'] = "Valores imcompletos, Recargue la pagina";
            return $output;
        }

        $postEvent = PostEvent::find($id);
        if(is_null($postEvent)){
            $output['msg'] = "Elemento no encontrado";
            return $output;            
        }
        
        $postEvent->load('owner');
        $postEvent->owner->load('profile_img');
        $postEvent->load('media');
        $postEvent->load('event_detail');


        if($postEvent->status == "review"){//mostrar solo para admin o propietario 
            if(Auth::guard('web')->check()){//usuario logeado 
                //Usuariologeado es un invitado que no es el creado del post 
                if(Auth::user()->hasRole('Invitado') && Auth::user()->id != $postEvent->creator_id){
                    $output["msg"] = "El elemento no ha sido aprobado";
                    return $output;                    
                }
            }else{ //Usuairo no logeado 
                $output["msg"] = "El elemento no ha sido aprobado no";
                return $output;
            }
        }

        $output["code"] = 1;
        $output["data"] = $postEvent;
        $output["msg"] = "Elemento recuperado";

        return $output;
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
