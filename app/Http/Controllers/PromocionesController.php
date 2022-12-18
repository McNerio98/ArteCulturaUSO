<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\UsersHelper;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Promotion;
use App\PostEvent;
use App\Memory;
use App\Recurso;
use App\User;

use Storage;


class PromocionesController extends Controller
{
    public function index(){
		if( ! Auth::user()->can('ver-promociones')){ //poner esto en los de arriba 
            return redirect()->route('dashboard');
        };	        
        $request_users = UsersHelper::usersRequest();
        return view('admin.promociones.index' , ['ac_option' =>'promociones' , 'request_users' => $request_users]);        
    }

    public function create(){
		if( ! Auth::user()->can('crear-promociones')){ //poner esto en los de arriba 
            return redirect()->route('dashboard');
        };	        
        $request_users = UsersHelper::usersRequest();
        return view('admin.promociones.create' , ['ac_option' =>'promociones' , 'request_users' => $request_users]);           
    }

    public function show(){
		if( ! Auth::user()->can('ver-promociones')){ //poner esto en los de arriba 
            return redirect()->route('dashboard');
        };	        
        $request_users = UsersHelper::usersRequest();
        return view('admin.promociones.show' , ['ac_option' =>'promociones' , 'request_users' => $request_users]);           
    }  

    public function getall(){
        $output = [
            "code" => 0,
            "data" => null,
            "msg" => "",
        ];   

        $list = Promotion::all();
        
        $output["code"] = 1;
        $output["data"] = $list;
        $output["msg"] = "Elementos recuperados";      

        return $output;        
    }
    
    public function find(Request $reques,$id){
        $output = [
            "code" => 0,
            "data" => null,
            "msg" => "",
        ];
        
        $promo = Promotion::find($id);
        if(!$promo){
            $output["code"] = 0;
            $output["msg"] = "No encontrado";
            return $output;
        }        

        $output["code"] = 1;
        $output["data"] = $promo;
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
        $id = intval($request->promo["id"]);

        if($id == 0 && !$user->can("crear-promociones")){
            $output["msg"] = "Operación denegada";
            return $output;
        }

        if($id != 0 && !$user->can("editar-promociones")){
            $output["msg"] = "Operación denegada";
            return $output;            
        }

        $params = [
            "promo.id" => "required|numeric",
            "promo.title" => "required|max:250",
            "promo.description" => "required",
            "promo.type_ads" => "required|numeric|min:1",
            "promo.item_id" => "required |numeric|min:1",
        ];

        $validator = Validator::make($request->all(),$params);            
        if($validator->fails()){
            $output["msg"] = "Campos imcompletos";
            return $output;
        }    

        /**Validaciones sobre apartados permitidos para promociones */
        $itemBaseId = intval($request->promo["item_id"]);
        $itemtBaseType = intval($request->promo["type_ads"]);
        $itemBase = null;
        switch($itemtBaseType){
            case 1: { //PostEvent
                $itemBase = PostEvent::find($itemBaseId);
                break;
            }
            case 2: { //Homenajes/Biografias
                $itemBase = Memory::find($itemBaseId);
                break;
            }
            case 3: { //Recurso
                $itemBase = Recurso::find($itemBaseId);
                break;
            }
            case 4: {//Perfil
                $itemBase = User::find($itemBaseId);
                break;
            }                                
        }

        if(!$itemBase){
            $output["msg"] = "El elemento no existe para el tipo especificado";
            return $output;
        }

        if($itemtBaseType == 4 && !$itemBase->hasRole('Invitado')){
            $output["msg"] = "Promociones solo para perfiles no administrativos";
            return $output;
        }
        /**END / Validaciones sobre apartados permitidos para promociones */


        if($id == 0){
            $promo = new Promotion();
            $limite = 6;
            if((count(Promotion::all()) + 1) > $limite){
                $output["msg"] = "Limite maximo permitido 6 promociones";
                return $output;
            }
        }else{
            $promo = Promotion::find($id);
        }

        if(!$promo){
            $output["msg"] = "Ítem no encontrado";
            return $output;            
        }           

        DB::beginTransaction();
        try{

            $promo->title               = $request->promo["title"];
            $promo->content         = $request->promo["description"];
            $promo->type_ads    = $request->promo["type_ads"];
            $promo->item_id         = $request->promo["item_id"];


            //Si viene data y el nombre del anterior es nullo sifnigica que es nuevo 
            
            $pathname = "files/images/";
            #Actualizando nueva imagen
            if(!is_null($request->promo["image"]["data"])){
                #Eliminando previa 
                if(!is_null($promo->name_img) && Storage::disk('local')->exists($pathname . $promo->name_img)){
                    if(! Storage::disk('local')->delete($aux_path . $promo->name_img) ){
                        throw new \Exception("No se logró eliminar la imagen previa");
                    }                       
                } 

                #Subiendo nueva 
                $dataFileOnly = substr($request->promo["image"]["data"], strpos($request->promo["image"]["data"], ',') + 1);
                $dataFile = base64_decode($dataFileOnly);
                $extensionFile = explode(".",$request->promo["image"]["name"]);
                $extensionFile = $extensionFile[count($extensionFile)-1];     
                $filename = "promo".uniqid()."_".time().".".$extensionFile;
                $path_store = $pathname.$filename;
                $promo->name_img    = $filename;
                Storage::disk('local')->put($path_store, $dataFile);                
            }

            $promo->save();
            $promo->refresh();
            DB::commit();
            if($id== 0){
                $output["msg"] = "Elemento creado promo";
            }else{
                $output["msg"] = "Elemento actualizado";
            }

            $output["code"] = 1;
            $output["data"] = $promo;              
        }catch(\Trowable $ex){
            DB::rollback();
            $output["msg"] = "Error en la operación, consulte soporte técnico.";            
            //$output["msg"] = "Error al ".$ex->getMessage(); //for debug            
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
        $promo = Promotion::find($id);        
        if(!$promo){
            $output["msg"] = "No existe el elemento";
            return $output;
        }

        //Verificando permisos 
        if(!$user->can('eliminar-promociones')){
            $output["msg"] = "Operación denegada";
            return $output;
        }  

        try{
            //Eliminando archivo imagen del almacenamiento
            if(!is_null($promo->name_img)){
                $aux_path = 'files/images/' . $promo->name_img;
                if(Storage::disk('local')->exists($aux_path)){
                    if(! Storage::disk('local')->delete($aux_path) ){
                        throw new \Exception("No se logró eliminar el archivo imagen");
                    }   
                }                 
            }

            $promo->delete();           
            $output["code"] = 1;
            $output["msg"] = "Deleted Successfully";    
        }catch(\Throwable $ex){
            $output["msg"] = "Error al eliminar promocion";
            //$output["msg"] = "Error al actualizar promocion ".$ex->getMessage(); //for debug                  
        }

        return $output;
    }
}
