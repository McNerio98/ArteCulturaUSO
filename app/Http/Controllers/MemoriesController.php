<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Memory;
use App\FilesOnMemory;
use Storage;

class MemoriesController extends Controller
{

    public function store(Request $request){
        $salida = [
            "code" => 0,
            "data" => null,
            "msg" => "",
        ];

        $salida["extra"] = "";

        $user = Auth::user();
        if(!$user->can("crear-reseñas")){
            $salida["msg"] = "Operación denegada";
            return $salida;
        }
        
        //Validaciones de campos
        $validator = Validator::make($request->all(), [
            "area" => "required",
            "name" => "required",
            "other_name" => "required",
            "birth_date" => "required|date",
            "content"   => "required",
            "type" => ["required",Rule::in(["biography","memory"])]
        ]);

        if($validator->fails()){
            $salida["msg"] = "Campos imcompletos";
            return $salida;
        }

        if($request->type == "memory"){
            $validator2 = Validator::make($request->all(),[
                "death_date" => "required | date"
            ]);

            if(!$validator2->fails()){
                $salida["msg"] = "Campos imcompletos";
                return $salida;                
            }
        }


        DB::beginTransaction();
        $MemoItem = new Memory();
        $id_img_presentation = 0;
        try{
            $MemoItem->area                 = $request->area;
            $MemoItem->name              = $request->name;
            $MemoItem->other_name   = $request->other_name;
            $MemoItem->birth_date       = $request->birth_date;
            $MemoItem->content           = $request->content;
            $MemoItem->type                 = $request->type;
            $MemoItem->creator_id       = $user->id;
            if($request->type == "memory"){
                $MemoItem->death_date = $request->death_date;
            }
            $MemoItem->save();
            
            if($request->media){
                $limite_carga = 70;
                $index = 0;
                $files = $request->media;
                if(count($files) >= $limite_carga){
                    throw new \Exception("Límite de carga superado, máximo ".$limite_carga." archivos");
                }

                while($index < count($files)){
                    if($files[$index]["type"] == "image" || $files[$index]["type"] == "docfile"){
                        $data = substr($files[$index]['data'], strpos($files[$index]['data'], ',') + 1);
                        $data = base64_decode($data);            
                        
                        $extension = explode(".",$files[$index]['filename']);
                        //obtener el ultimo elemento del array creado (explode)
                        $extension = $extension[count($extension)-1];
                        $filename = "";
                        if($files[$index]['type'] == "image"){ //is a img
                            $filename = uniqid()."_".time().".".$extension;
                            $pathname = "files/images/";
                        }else if($files[$index]['type'] == "docfile"){ //is a document
                            $filename = $files[$index]['filename'];
                            $pathname = "files/docs/pe".$MemoItem->id."/";
                        }

                        $FileMemory = new FilesOnMemory();
                        $FileMemory->memory_id      = $MemoItem->id;
                        $FileMemory->name               = $filename;
                        $FileMemory->type_file          = $files[$index]["type"];
                        $FileMemory->save();

                        $path_store = $pathname.$filename;
                        Storage::disk('local')->put($path_store, $data);                        
                        
                        if($id_img_presentation == 0 && $FileMemory->type_file === "image" && $files[$index]["presentation"] == "true"){
                            $id_img_presentation = $FileMemory->id;
                        }
                    }
                    $index++;
                }
            }

            if($id_img_presentation !== 0){
                $MemoItem->presentation_img = $id_img_presentation;
                $MemoItem->save();
            }            

            DB::commit();
            $salida["code"] = 1;
            $salida["data"] = [
                "memory" => $MemoItem,
            ];
        }catch(\Throwable $ex){
            DB::rollback();
            //$salida["msg"] = "Error en la operación, consulte soporte técnico.";
            $salida['msg'] = "Error: " . $ex->getMessage(); //for debugin            
        }

        return $salida;
    }
}
