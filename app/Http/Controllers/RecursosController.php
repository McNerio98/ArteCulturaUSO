<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helper\UsersHelper;
use App\Recurso;
use App\FilesOnResource;
use Illuminate\Support\Facades\DB;
use App\User;
use App\ResourceType;
use Storage;

class RecursosController extends Controller
{
    public function indexadmin(){
		if( ! Auth::user()->can('ver-recursos')){ 
            return redirect()->route('dashboard');
        };
		$request_users = UsersHelper::usersRequest();
    	return view('admin.recursos.index' , ['ac_option' =>'recursos' , 'request_users' => $request_users]);        
    }

    public function show(){
    	return view('recursos.show');        
    }

    public function tipos(){
        $output = [
            "code" => 0,
            "data" => null,
            "msg" => ""
        ];

        $output["data"] = ResourceType::all();
        $output["code"] = 1;

        return $output;
    }



    public function createadmin(Request $request){

        $idr = $request->input('idr');
        $isUpdate = ($idr != null && intval($idr) > 0);

        if($isUpdate){
            if( ! Auth::user()->can('editar-recursos') || Recurso::find($idr) == null){ 
                return redirect()->route('dashboard');
            };	
        }else{
            if( ! Auth::user()->can('crear-recursos')){ //poner esto en los de arriba 
                return redirect()->route('dashboard');
            };	
        }

		$request_users = UsersHelper::usersRequest();
    	return view('admin.recursos.create' , ['ac_option' =>'recursos' , 'request_users' => $request_users]);        
    }

    public function showadmin($id){

        $element = Recurso::find($id);
        $user = Auth::user();

        if(! $element){
            return redirect()->route('dashboard');
        }
        
		if( ! Auth::user()->can('ver-recursos') && $element->creator_id != $user->id){ 
            return redirect()->route('dashboard');
        };        
        
		$request_users = UsersHelper::usersRequest();
    	return view('admin.recursos.show' , ['ac_option' =>'recursos' , 'request_users' => $request_users]);        
    }

    public function getAllAdmin(Request $request){
        $output = [
            "code" => 0,
            "data" => null,
            "msg" => "",
        ];            

        /**
         * Validar aunq halla una parte publica  
         * Porque se pueden filtrar, por ejemplo aprobador si lo ubiera 
         * Actualmente no pasa ningun filtrado asi que se recuperan todos 
         */
		if( ! Auth::user()->can('ver-recursos')){ 
            $output["msg"] = "Acción no permitida";
            return $output;
        };
        $queryParams = [
            "page" => $request->page,
            "per_page" => $request->per_page,
            "filter" => $request->filter
        ];
        return redirect()->action('RecursosController@getAllPublic',$queryParams);        
    }

    public function getAllPublic(Request $request){
        $output = [
            "code" => 0,
            "data" => null,
            "msg" => "",
            'pagination' => null
        ];       

        $per_page = ($request->per_page === null || $request->per_page > 15) ? 15 : $request->per_page;

        $builder = Recurso::with('presentation_model')->join('resource_types AS ret', 'ret.id', '=', 'recursos.tipo_id')->select('recursos.*','ret.name AS resource_type');
        if($request->filter != "ALL" && $request->filter != null){
            $builder->where('tipo_id'  , $request->filter);
        }

        $builder->orderBy('recursos.id','desc');
        $result = $builder->paginate($per_page);

        $output["data"] = $result->items();
        $output["pagination"] = [
            'total' 				=>$result->total(),
            'current_page'  => $result->currentPage(),
            'per_page'      => $result->perPage(),
            'last_page'     => $result->lastPage(),
            'from'          => $result->firstItem(),
            'to'            => $result->lastPage(),
        ];  
        $output["code"] = 1;
        $output["msg"] = "Elementos recuperados";           
         
        return $output;        
    }

    public function find(Request $reques,$id){
        $output = [
            "code" => 0,
            "data" => null,
            "msg" => "",
        ];

        //$recurso = Recurso::find($id)->leftJoin('resource_types AS ret', 'ret.id', '=', 'recursos.tipo_id')->select('recursos.*','ret.name AS resource_type')->first();
        $recurso = Recurso::leftJoin('resource_types AS ret', 'ret.id', '=', 'recursos.tipo_id')
        ->where('recursos.id',$id)
        ->select('recursos.*','ret.name AS resource_type')->first();

        if(!$recurso){
            $output["code"] = 0;
            $output["msg"] = "No encontrado";
            return $output;
        }

        $recurso->load("media");
        $recurso->load("presentation_model");
        
        $output["code"] = 1;
        $output["data"] = $recurso;
        $output["msg"] = "Elemento recuperado";           

        return $output;
    }

    public function upsert(Request $request){
        $output = [
            "code" => 0,
            "data" => null,
            "msg" => "",
        ];

        $user = Auth::user();
        if($request->resource["id"] == 0){
            $recurso = new Recurso();
        }else{
            $recurso = Recurso::find($request->resource["id"]);
        }   

        if(!$recurso){
            $output["msg"] = "Ítem no encontrado";
            return $output;            
        }   

        if(!$user->can("crear-recursos") && $request->resource["id"] == 0){
            $output["msg"] = "Operación denegada";
            return $output;
        }


        //Si no puede editar recursos y efectivamente es un id de edicion, pero si tambien no es el propietario del recurso 
        if((!$user->can("editar-recursos") && $request->resource["id"] != 0) && !($recurso->creator_id == $user->id)){
            $output["msg"] = "Operación denegada";
            return $output;
        }        

        $params = [
            "resource.id" => "required|numeric",
            "resource.name" => "required|max:250",
            "resource.description" => "required|min:5",
            "resource.tipo_id" => "required|numeric|min:1",
            //"resource.presentation_img" => "required|numeric"
        ];

        $validator = Validator::make($request->all(),$params);        

        if($validator->fails()){
            $output["msg"] = "Campos imcompletos";
            return $output;
        }             

        
        DB::beginTransaction();
        $idImgPresentation = 0;
        try{
            
            $recurso->name            = $request->resource["name"];
            $recurso->content         = $request->resource["description"];
            $recurso->tipo_id           = $request->resource["tipo_id"];
            $recurso->creator_id    = $user->id;
            $recurso->save();

            $limite_carga = 10;
            $index = 0;
            $files = $request->media;
            if(count($files) >= $limite_carga){
                throw new \Exception("Límite de carga superado, máximo ".$limite_carga." archivos");
            }            

            #Agregar contenido nuevo (todo los que tengan id = 0)
            while($index < count($files)){
                if($files[$index]["id"] == 0){
                    $FileResource = new FilesOnResource();

                    if($files[$index]["type_file"] == "image" || $files[$index]["type_file"] == "docfile"){
                        $dataFile = substr($files[$index]['data'], strpos($files[$index]['data'], ',') + 1);
                        $dataFile = base64_decode($dataFile);            
                        $extensionFile = explode(".",$files[$index]['name']);               
                        $extensionFile = $extensionFile[count($extensionFile)-1];     
    
                        if($files[$index]['type_file'] == "image"){ //is a img
                            $filename = "me".uniqid()."_".time().".".$extensionFile;
                            $pathname = "files/images/";
                        }else if($files[$index]['type_file'] == "docfile"){ //is a document
                            $filename = $files[$index]['name'];
                            //Crear folder especifico para no remplazar de otra publicacion 
                            $pathname = "files/docs/rc".$recurso->id."/";
                        }
    
                        $FileResource->recurso_id       = $recurso->id;
                        $FileResource->name              = $filename;
                        $FileResource->type_file          = $files[$index]["type_file"];
                        $FileResource->save();
    
                        $path_store = $pathname.$filename;
                        Storage::disk('local')->put($path_store, $dataFile);                                            
                        
                        //Nueva imagen de presentacion 
                        if($FileResource->type_file === "image" && isset($files[$index]["presentation"]) && $files[$index]["id"] == 0){
                            $idImgPresentation = $FileResource->id;
                        }
                    }
                    //Video no existe aqui 
                }

                #siemprese aumenta para llegar al final de los archivos
                $index++;
            }            

            #Creando auxiliar porque el de la solicitud no es modificable (Da error)
            #Eliminacion de multimedias existentes para caso de actualización
            #En la eliminacion se puede incluir eliminacion de imagen de presentacion por nueva actualización             
            $mediadrop_ids = $request->mediadrop_ids;
            #Si cargo imagen de presentacion entonces se asocia 
            if($idImgPresentation !== 0){
                /**Verificando si tenia una anteriormente en caso de actualizacion, se agrega para eliminar */
                if(!is_null($recurso->presentation_img) && $recurso->presentation_img != 0){
                    array_push($mediadrop_ids,$recurso->presentation_img);
                }

                $recurso->presentation_img = $idImgPresentation;
                $recurso->save();
            } 

            $count_drop = 0;
            if($request->resource["id"] != 0 && count($mediadrop_ids) > 0){
                foreach($recurso->media as $candidato){
                    foreach($mediadrop_ids as $dropid){
                        if($candidato->id === intval($dropid)){
                            $aux_path = "";
                            #Para video se omite eliminacion de storage y se borra el modelo solamente 
                            switch($candidato->type_file){
                                case 'image': {$aux_path = "files/images/".$candidato->name;break;}
                                case 'docfile': {$aux_path = "files/docs/rc".$candidato->id."/".$candidato->name;break;}
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
                        $output["extra"] = "Se eliminaron " .$count_drop . " Elementos";
                        break; //break 2 for, all element was deleted 
                    }
                }              
            }            
            
            
            #End / Carga de archivos multimedias 
            DB::commit();
            $recurso->refresh();


            if($request->resource["id"] == 0){
                $output["msg"] = "Elemento creado";
            }else{
                $output["msg"] = "Elemento actualizado";
            }

            $output["code"] = 1;
            $output["data"] = $recurso;     
        }catch(\Trowable $ex){
            DB::rollback();
            $output["msg"] = "Error en la operación, consulte soporte técnico.";
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
        $recurso = Recurso::find($id);
        if(!$recurso){
            $output["msg"] = "No existe el elemento";
            return $output;
        }

        //Verificando permisos 
        if(!$user->can('eliminar-recursos') && !($recurso->creator_id == $user->id)){
            $output["msg"] = "Operación denegada";
            return $output;
        }        

        $creator = User::find($recurso->creator_id);
        if(!$creator){
            $output["msg"] = "Inconsistencia de datos";
            return $output;
        }        


        try{
            //Eliminacion de Archivos multimedia 
            foreach($recurso->media as $del){
                $aux_path = "";
                switch($del->type_file){
                    case 'image': $aux_path = "files/images/".$del->name;break;
                    case 'docfile': $aux_path = "files/docs/rc".$recurso->id."/".$del->name;break; //aplicar filtro, solo agregar id de post 
                }            
                //Si no esta es porque es video, solo se elimina mas abajo 
                if(trim($aux_path) !== "" && Storage::disk('local')->exists($aux_path)){
                    if(! Storage::disk('local')->delete($aux_path) ){
                        throw new \Exception("No se logró eliminar algunos medios: ".$del->name);
                    }   
                } 
                
                //No seria necesario, porque se agrego la eliminacion en casada pero siempre se dejo 
                $del->delete();            
            }
            $recurso->delete();           
            $output["code"] = 1;
            $output["msg"] = "Deleted Successfully";             
        }catch(\Throwable $ex){
            $output["msg"] = "Error al eliminar recurso";
            //$output["msg"] = "Error al actualizar publicacion ".$ex->getMessage(); //for debug            
        }        


        return $output;
    }
}
