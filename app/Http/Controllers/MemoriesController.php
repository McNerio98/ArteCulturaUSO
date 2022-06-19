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
		$this->middleware('auth');
		//adroles verifica que no sea un invitado el que esta intentado ver la dashboard 
		$this->middleware('adroles',['except' => ['indexpublic']]);
        
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

    public function getAllAdminl(){
        //Validar aunq halla una parte publica 
		if( ! Auth::user()->can('ver-reseñas')){ //poner esto en los de arriba 
            //usar ajax 
        };		        
    }

    #Si por ejemplo se implementa apartado de revision (solo verian los que estanb aprobador por ejemplo)
    public function getAllPublic(){

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
        $memory->load("img_presentation");
        
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
            $files = $request["files"];
            if(count($files) >= $limite_carga){
                throw new \Exception("Límite de carga superado, máximo ".$limite_carga." archivos");
            }            

            while($index < count($files)){
                if($files[$index]["type"] == "image" || $files[$index]["type"] == "docfile"){
                    $dataFile = substr($files[$index]['data'], strpos($files[$index]['data'], ',') + 1);
                    $dataFile = base64_decode($data);            
                    $extensionFile = explode(".",$files[$index]['filename']);               
                    $extensionFile = $extensionFile[count($extensionFile)-1];     

                    if($files[$index]['type'] == "image"){ //is a img
                        $filename = "me".uniqid()."_".time().".".$extension;
                        $pathname = "files/images/";
                    }else if($files[$index]['type'] == "docfile"){ //is a document
                        $filename = $files[$index]['filename'];
                        //Crear folder especifico para no remplazar de otra publicacion 
                        $pathname = "files/docs/me".$memory->id."/";
                    }

                    $FileMemory = new FilesOnMemory();
                    $FileMemory->memory_id      = $memory->id;
                    $FileMemory->name               = $filename;
                    $FileMemory->type_file          = $files[$index]["type"];
                    $FileMemory->save();

                    $path_store = $pathname.$filename;
                    Storage::disk('local')->put($path_store, $dataFile);                                            

                    if($idImgPresentation == 0 && $FileMemory->type_file === "image" && $files[$index]["presentation"] == "true"){
                        $idImgPresentation = $FileMemory->id;
                    }

                }
                $index++;
            }
            
            #Si cargo imagen de presentacion entonces se asocia 
            if($idImgPresentation !== 0){
                $memory->presentation_img = $idImgPresentation;
                $memory->save();
            }      

            #End / Carga de archivos multimedias 
            DB::commit();
            $memory->refresh();

            $output["code"] = 1;
            $output["data"] = $memory;
            if($request->memory->id == 0){
                $output["msg"] = "Elemento creado";
            }else{
                $output["msg"] = "Elemento actualizado";
            }
            
        }catch(\Throwable $ex){
            DB::rollback();
            $output["msg"] = "Error en la operación, consulte soporte técnico.";
            //$output['msg'] = "Error: " . $ex->getMessage(); //for debugin            
        }

        return $output;
    }
}
