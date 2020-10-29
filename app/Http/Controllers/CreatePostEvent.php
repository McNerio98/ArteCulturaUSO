<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\PostEvent;

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
            $postEvent->type_post = isset($request->price) ? "event" : "post";
            $postEvent->creator_id = $user->id;

            echo $request->media;

        }catch(\Exception $e){

        }

        // return $salida;

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
