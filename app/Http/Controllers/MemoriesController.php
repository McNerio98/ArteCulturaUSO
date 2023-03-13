<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Memory;
use App\FilesOnMemory;
use App\Helper\UsersHelper;
use App\User;
use Storage;

class MemoriesController extends Controller
{
    public function __construct(){
		//Todos requieren estar logeados 
		$this->middleware('auth',['except' => ['getAllPublic','show','find']]);
		//adroles verifica que no sea un invitado el que esta intentado ver la dashboard 
		$this->middleware('adroles',['except' => ['getAllPublic','show','find']]);
        
        //Only for example 
        /**
         * $this->middleware('auth', ['only' => ['create', 'store', 'edit', 'delete']]);
         *  $this->middleware('auth', ['except' => ['index', 'show']]);
         */
    }


    #Muestra vista 
    public function indexadmin(){
		if( ! Auth::user()->can('ver-biografias')){ //poner esto en los de arriba 
            return redirect()->route('dashboard');
        };		
		$request_users = UsersHelper::usersRequest();
    	return view('admin.memories.index' , ['ac_option' =>'memories' , 'request_users' => $request_users]);        
    }

    public function show(){
        return view('memories.show');
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
		if( ! Auth::user()->can('ver-biografias')){ 
            $output["msg"] = "Acción no permitida";
            return $output;
        };
        $queryParams = [
            "page" => $request->page,
            "per_page" => $request->per_page,
            "filter_letter" => $request->filter_letter
        ];
        return redirect()->action('MemoriesController@getAllPublic',$queryParams);
    }
    

    #Si por ejemplo se implementa apartado de revision (solo verian los que estanb aprobador por ejemplo)
    public function getAllPublic(Request $request){
        $output = [
            "code" => 0,
            "data" => null,
            "msg" => "",
            'pagination' => null
        ];        
        #Actualmente no pasa ningun filtrado asi que se recuperan todos 
        #Usar paginacion 

        $per_page = ($request->per_page === null || $request->per_page > 15) ? 15 : $request->per_page;

        $builder = Memory::with('presentation_model');
        if($request->filter_letter != "ALL" && $request->filter_letter != null){
            $builder->where('name'  ,   'LIKE'  ,   $request->filter_letter.'%');
            $builder->orWhere('other_name'  ,   'LIKE'  ,   $request->filter_letter.'%');
        }

        $builder->orderBy('id','desc');
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

    public function find($id){
        $output = [
            "code" => 0,
            "data" => null,
            "msg" => "",
        ];

        $memory = Memory::find($id);
        if(!$memory){
            $output["code"] = 0;
            $output["msg"] = "No encontrado";
            return $output;
        }

        $memory->load("media");
        $memory->load("presentation_model");
        
        $output["code"] = 1;
        $output["data"] = $memory;
        $output["msg"] = "Elemento recuperado";        
        
        return $output;
    }


    public function create(Request $request){

        
        $idm = $request->input('idm');
        $isUpdate = ($idm != null && intval($idm) > 0);

        $memory = Memory::find($idm);
        $user = Auth::user();

        if($isUpdate){
            if( (! Auth::user()->can('editar-biografias') && $memory->creator_id != $user->id ) || $memory == null){ 
                return redirect()->route('dashboard');
            };	
        }else{
            if( ! Auth::user()->can('crear-biografias')){ //poner esto en los de arriba 
                return redirect()->route('dashboard');
            };	
        }

			
		$request_users = UsersHelper::usersRequest();
    	return view('admin.memories.create' , ['ac_option' =>'memories' , 'request_users' => $request_users]);
    }



    public function showadmin($id){

        $user = Auth::user();
        $element = Memory::find($id);

        if(! $element){
            return redirect()->route('dashboard');
        }


		if( ! Auth::user()->can('ver-biografias') && $element->creator_id != $user->id){ //poner esto en los de arriba 
            return redirect()->route('dashboard');
        };	        
        
		$request_users = UsersHelper::usersRequest();
    	return view('admin.memories.show' , ['ac_option' =>'memories' , 'request_users' => $request_users]);
    }

    #Create or update
    public function upsert(Request $request){
        $output = [
            "code" => 0,
            "data" => null,
            "msg" => "",
        ];

        $user = Auth::user();
        $isUpdate = (intval($request->memory["id"]) != 0);

        if($isUpdate){
            $memory = Memory::find($request->memory["id"]);
        }else{
            $memory = new Memory();
        }


        if(!$memory){//For update case only 
            $output["msg"] = "Ítem no encontrado";
            return $output;            
        }


        if(!$user->can('crear-biografias') && !$isUpdate){
            $output["msg"] = "Operación denegada";
            return $output;
        }

        if($isUpdate && !$user->can('editar-biografias')  && $memory->creator_id != $user->id){
            $output["msg"] = "Operación denegada";
            return $output;
        }        


        //Validaciones de campos
        $params = [
            "memory.id" => "required|numeric",
            "memory.area" => "required",
            "memory.name" => "required",
            "memory.birth_date"   => "required | date",
            "memory.content"   => "required",
            "memory.type" => ["required",Rule::in(["biography","memory"])]
        ];

        if($request->memory["type"] == "memory"){
            $params["memory.death_date"] = "required | date";
        }
        $validator = Validator::make($request->all(),$params);

        if($validator->fails()){
            $output["msg"] = "Campos imcompletos";
            return $output;
        }



        DB::beginTransaction();
        $idImgPresentation = 0;
        $showMsgError = false;
        try{

            $memory->area                 = $request->memory["area"];
            $memory->name              = $request->memory["name"];
            $memory->other_name   = $request->memory["other_name"];//puede ser nulo
            $memory->birth_date       = $request->memory["birth_date"];
            $memory->content           = $request->memory["content"];
            $memory->type                 = $request->memory["type"];
            if(!$isUpdate){
                $memory->creator_id       = $user->id;
            }
            
            if($request->memory["type"] == "memory"){
                $memory->death_date = $request->memory["death_date"];
            }
            $memory->save();            

            #Carga de archivos multimedias 
            $limite_carga = 10;
            $index = 0;
            $files = $request->media;
            if(count($files) > $limite_carga){
                $showMsgError = true;
                throw new \Exception("Límite de carga superado, máximo ".$limite_carga." archivos");
            }            


            #Agregar contenido nuevo (todo los que tengan id = 0)
            while($index < count($files)){
                if($files[$index]["id"] == 0){
                    $FileMemory = new FilesOnMemory();

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
                            $pathname = "files/docs/me".$memory->id."/";
                        }
    
                        $FileMemory->memory_id      = $memory->id;
                        $FileMemory->name               = $filename;
                        $FileMemory->type_file          = $files[$index]["type_file"];
                        $FileMemory->save();
    
                        $path_store = $pathname.$filename;
                        if(!Storage::disk('local')->put($path_store, $dataFile)){
                            $showMsgError = true;
                            throw new \Exception("No se logro guardar el archivo");
                        }
                        
                        //Nueva imagen de presentacion 
                        if($FileMemory->type_file === "image" && isset($files[$index]["presentation"]) && $files[$index]["id"] == 0){
                            $idImgPresentation = $FileMemory->id;
                        }
    
                    }
    
                    if($files[$index]["type_file"] == "video"){
                        $FileMemory->memory_id      = $memory->id;
                        $FileMemory->name               = $files[$index]['name'];
                        $FileMemory->type_file          = $files[$index]["type_file"];
                        $FileMemory->save();                    
                    }
                    /**Cualquier otro tipo se omite **/
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
                if(!is_null($memory->presentation_img) && $memory->presentation_img != 0){
                    array_push($mediadrop_ids,$memory->presentation_img);
                }

                $memory->presentation_img = $idImgPresentation;
                $memory->save();
            }
        

            $count_drop = 0;
            if($isUpdate && count($mediadrop_ids) > 0){
                foreach($memory->media as $candidato){
                    foreach($mediadrop_ids as $dropid){
                        if($candidato->id === intval($dropid)){
                            $aux_path = "";
                            #Para video se omite eliminacion de storage y se borra el modelo solamente 
                            switch($candidato->type_file){
                                case 'image': {$aux_path = "files/images/".$candidato->name;break;}
                                case 'docfile': {$aux_path = "files/docs/me".$candidato->id."/".$candidato->name;break;}
                            }

                            if(trim($aux_path) !== "" && Storage::disk('local')->exists($aux_path)){
                                if(! Storage::disk('local')->delete($aux_path) ){
                                    $showMsgError = true;
                                    throw new \Exception("No se logró eliminar algunos medios".$candidato->name);
                                }                                   
                            }

                            if(!$candidato->delete()){
                                $showMsgError = true;
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
            $memory->refresh();


            if(!$isUpdate){
                $output["msg"] = "Elemento creado";
            }else{
                $output["msg"] = "Elemento actualizado";
            }

            $output["code"] = 1;
            $output["data"] = $memory;            
        }catch(\Throwable $ex){
            DB::rollback();
            if($showMsgError){
                $output['msg'] = "Error: " . $ex->getLine() . " - " . $ex->getMessage(); //for debugin            
            }else{
                $output["msg"] = "Error en la operación, consulte soporte técnico.";
            }
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
        $memory = Memory::find($id);
        if(!$memory){
            $output["msg"] = "No existe el elemento";
            return $output;
        }

        //Verificando permisos 
        if(!$user->can('eliminar-biografias') && !($memory->creator_id == $user->id)){
            $output["msg"] = "Operación denegada";
            return $output;
        }

        $creator = User::find($memory->creator_id);
        if(!$creator){
            $output["msg"] = "Inconsistencia de datos";
            return $output;
        }

        try{
            //Eliminacion de Archivos multimedia 
            foreach($memory->media as $del){
                $aux_path = "";
                switch($del->type_file){
                    case 'image': $aux_path = "files/images/".$del->name;break;
                    case 'docfile': $aux_path = "files/docs/me".$memory->id."/".$del->name;break; //aplicar filtro, solo agregar id de post 
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
            $memory->delete();           
            $output["code"] = 1;
            $output["msg"] = "Deleted Successfully";             
        }catch(\Throwable $ex){
            $output["msg"] = "Error al eliminar biografia";
            //$output["msg"] = "Error al actualizar publicacion ".$ex->getMessage(); //for debug            
        }

        return $output;        
    }
}
