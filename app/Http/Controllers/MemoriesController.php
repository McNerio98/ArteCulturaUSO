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
use Storage;

class MemoriesController extends Controller
{
    public function __construct(){
		//Todos requieren estar logeados 
		$this->middleware('auth',['except' => ['']]);
		//adroles verifica que no sea un invitado el que esta intentado ver la dashboard 
		$this->middleware('adroles',['except' => ['indexpublic','']]);
        
        //Only for example 
        /**
         * $this->middleware('auth', ['only' => ['create', 'store', 'edit', 'delete']]);
         *  $this->middleware('auth', ['except' => ['index', 'show']]);
         */
    }


    #Muestra vista 
    public function index(){
		if( ! Auth::user()->can('ver-reseñas')){ //poner esto en los de arriba 
            return redirect()->route('dashboard');
        };		
		$request_users = UsersHelper::usersRequest();
    	return view('admin.memories.index' , ['ac_option' =>'memories' , 'request_users' => $request_users]);        
    }

    public function indexpublic(){

    }

    public function getAllAdmin(){
        $output = [
            "code" => 0,
            "data" => null,
            "msg" => "",
        ];            

        //Validar aunq halla una parte publica 
		if( ! Auth::user()->can('ver-reseñas')){ //poner esto en los de arriba 
            $output["msg"] = "Acción no permitida";
            return $output;
        };

        #Actualmente no pasa ningun filtrado asi que se recuperan todos 
        #Usar paginacion 

        $list = Memory::with('presentation_model')->get();

        $output["code"] = 1;
        $output["data"] = $list;
        $output["msg"] = "Elementos recuperados";           
         
        return $output;
    }

    #Si por ejemplo se implementa apartado de revision (solo verian los que estanb aprobador por ejemplo)
    public function getAllPublic(){
        $output = [
            "code" => 0,
            "data" => null,
            "msg" => "",
        ];        
        #Actualmente no pasa ningun filtrado asi que se recuperan todos 
        #Usar paginacion 

        $list = Memory::with('presentation_model')->get();

        $output["code"] = 1;
        $output["data"] = $list;
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
            $output["code"] = 404;
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


    public function create(){
		if( ! Auth::user()->can('crear-reseñas')){ //poner esto en los de arriba 
            return redirect()->route('dashboard');
        };				
		$request_users = UsersHelper::usersRequest();
    	return view('admin.memories.create' , ['ac_option' =>'memories' , 'request_users' => $request_users]);
    }

    public function showadmin(){
		if( ! Auth::user()->can('ver-reseñas')){ //poner esto en los de arriba 
            return redirect()->route('dashboard');
        };	        
		$request_users = UsersHelper::usersRequest();
    	return view('admin.memories.show' , ['ac_option' =>'memories' , 'request_users' => $request_users]);
    }

    #Create or update
    public function store(Request $request){
        $output = [
            "code" => 0,
            "data" => null,
            "msg" => "",
        ];

        $user = Auth::user();
        if(!$user->can("crear-reseñas")){
            $output["msg"] = "Operación denegada";
            return $output;
        }


        //Validaciones de campos
        $params = [
            "memory.id" => "required|numeric",
            "memory.area" => "required",
            "memory.name" => "required",
            "memory.other_name" => "required",
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




        if($request->memory["id"] == 0){
            $memory = new Memory();
        }else{
            $memory = Memory::find($request->memory["id"]);
        }

        //For update only 
        if(!$memory){
            $output["msg"] = "Ítem no encontrado";
            return $output;            
        }

        DB::beginTransaction();
        $idImgPresentation = 0;
        try{

            $memory->area                 = $request->memory["area"];
            $memory->name              = $request->memory["name"];
            $memory->other_name   = $request->memory["other_name"];
            $memory->birth_date       = $request->memory["birth_date"];
            $memory->content           = $request->memory["content"];
            $memory->type                 = $request->memory["type"];
            $memory->creator_id       = $user->id;
            if($request->memory["type"] == "memory"){
                $memory->death_date = $request->memory["death_date"];
            }
            $memory->save();            

            #Carga de archivos multimedias 
            $limite_carga = 70;
            $index = 0;
            $files = $request["media"];
            if(count($files) >= $limite_carga){
                throw new \Exception("Límite de carga superado, máximo ".$limite_carga." archivos");
            }            


            #Agregar contenido nuevo (toso los que tengan id = 0)
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
                        Storage::disk('local')->put($path_store, $dataFile);                                            
                        
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
            if($request->memory["id"] != 0 && count($mediadrop_ids) > 0){
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


            #End / Carga de archivos multimedias 
            DB::commit();
            $memory->refresh();


            if($request->memory["id"] == 0){
                $output["msg"] = "Elemento creado";
            }else{
                $output["msg"] = "Elemento actualizado";
            }

            $output["code"] = 1;
            $output["data"] = $memory;            
        }catch(\Throwable $ex){
            DB::rollback();
            //$output["msg"] = "Error en la operación, consulte soporte técnico.";
            $output['msg'] = "Error: " . $ex->getLine() . " - " . $ex->getMessage(); //for debugin            
        }

        return $output;
    }
}
