<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\PostEvent;
use App\FilesOnPostEvents;
use App\postsEventsMeta;
use Storage;

class CreatePostEvent extends Controller
{
    public function __construct(){
		$this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $postEvent = new PostEvent;

        $salida = [
            'codeStatus'  => 0,
            'msg'         => '',
            'operation'   => '',
            'objectData'  => null
        ];

        $user = Auth::user();
        //Se valida si la data esta completa tanto para un post o evento
        if(isset($request->precio)){
            $validator = Validator::make($request->all(),[
                'titulo' => 'required',
                'descripcion' => 'required',
                'precio' => 'required',
                'categoria' => 'required',
                'tipoevento' => 'required',
                'fechaevento' => 'required'
            ]);
            if($validator->fails()){
                return $salida['msg'] = "Valores imcompletos, intente de nuevo";
            }
        }else{
            $validator = Validator::make($request->all(),[
                'titulo' => 'required',
                'descripcion' => 'required'
            ]);
            if($validator->fails()){
                return $salida['msg'] = "Valores imcompletos, intente de nuevo";
            }
        }

        DB::beginTransaction();

        try{
            $postEvent->title = $request->titulo;
            $postEvent->content = $request->descripcion;
            $postEvent->type_post = isset($request->precio) ? "event" : "post";
            $postEvent->creator_id = $user->id;
            $postEvent->save();

            //Se llenan los datos para un evento
            if(isset($request->precio)){
                $keys = ["fechaevento", "precio", "tipoevento", "categoria"];
                foreach ($keys as $key => $value) {
                    $option = new postsEventsMeta;
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
                    $filesOnPostEvents = new FilesOnPostEvents;
                    if (preg_match('/^data:image\/(\w+);base64,/', $files[$numberfiles]['data'])
                        ||
                        preg_match('/^data:application\/(\w+);base64,/', $files[$numberfiles]['data'])
                    ) {
                        $data = substr($files[$numberfiles]['data'], strpos($files[$numberfiles]['data'], ',') + 1);

                        $data = base64_decode($data);
                        $filename = uniqid() . $files[$numberfiles]['filename'];
                        $pathname = $files[$numberfiles]['type'] == "image" ? ("files/images/" . $filename) : ("files/pdfs/" . $filename);

                        $filesOnPostEvents->id_post_event = $postEvent->id;
                        $filesOnPostEvents->name = $filename;
                        $filesOnPostEvents->path_file = $pathname;
                        $filesOnPostEvents->type_file = $files[$numberfiles]['type'];
                        $filesOnPostEvents->save();

                        Storage::disk('local')->put($pathname, $data);
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
            $salida['objectData'] = ["postevent" => $postEvent];
            $salida['msg'] = "Se han subido los archivos";
            $salida['codeStatus'] = 1;

        }catch(\Exception $e){
            DB::rollback();
            $salida['msg'] = "Error: " . $e;
        }

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
        //
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
