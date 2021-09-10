<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;
use App\UserMeta;
use App\TagsOnProfile;
use App\MediaProfile;
use App\PostEvent;


/*
** Obtiene los elementos, (Publicaciones o eventos) del perfil pasado como parametro 
*/
class ProfileController extends Controller
{
	public function __construct(){

	}

    public function index($id){
		$user = User::find($id);
		if(!$user){
			return redirect()->route('inicio');
		}

		if($user->active == 0 || $user->is_admin == 1){
			return redirect()->route('inicio');
		}
		
		return view("profile",['id_user_cur' => $id]);
	}

	public function editElement($id_user, $id_post){
		$e = PostEvent::find($id_post);
		if(!$e){
			return redirect()->route('inicio');
		}
		
		if(!Auth::user()->can('editar-publicaciones') &&  intval($e->creator_id) !==  intval(Auth::user()->id)){
			return redirect()->route('inicio');//permiso denegado 
		}

		//Inconsistencia visual 
		if( intval($e->creator_id) !==  intval($id_user) ){
			return redirect()->route('inicio');
		}

		return view("edit-item",['id_user_cur' =>  $id_user, 'id_elem_edit' => $id_post]);
	}
	

	public function elements(Request $request, $id){
		$salida = [
			"code" => 0,
			"msg"=>"",
			"data" => null,
			'pagination' => null
		];		

		$per_page = ($request->per_page === null)?15:$request->per_page;
		$result = PostEvent::where('creator_id',$id)->with('media')
						->leftJoin('dtl_events','post_events.id','=', 'dtl_events.event_id')
						->join('users','users.id','=','post_events.creator_id')
						->leftJoin('media_profiles AS mp','mp.id','users.img_profile_id')
						->select('post_events.*','dtl_events.event_date','dtl_events.frequency','dtl_events.has_cost','dtl_events.cost',
						'mp.path_file AS creator_profile','users.name AS creator_name','users.artistic_name AS creator_nickname','users.id AS creator_id')
						->orderBy('post_events.id','desc')->paginate($per_page);
		//AGREGAR EL ACTIVE ARRIVA 
		
        $salida["pagination"] = [
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

	public function show($id){
		$salida = [
			"code" => 0,
			"msg"=>"",
			"data" => null
		];

		$user = User::find($id);
		
		if(!$user){
			$salida["msg"] = "El usuario no existe";
			return $salida;
		}

		$metas_get 		= ['user_profile_description','user_profile_address','user_profile_notes']; //add another meta 
		$metas 				= UserMeta::whereIn('key',$metas_get)->where('user_id',$id)->get();
		$tags 				= 	User::select('tg.id','tg.name')->join('tags_on_profiles AS top','top.user_id','users.id')
									->join('tags AS tg','tg.id','top.tag_id')->where('users.id',$id)->get();
		$profile_media = MediaProfile::where('user_id',$user->id)->get(); 
		
        $fulldata = [
            'metas' => $metas,
            'user' => $user,
			'tags' => $tags,
			'media_profile' => $profile_media
        ];

        $salida = [
            'code' => 1,
            'data' => $fulldata,
            'msg' => 'Datos recuperados'
        ];
		return $salida;		
	}

	#Acceso publico, obtener modelo usuario y metadatos 
	public function aboutInfo($id){
		$salida = [
			"code" => 0,
			"msg"=>"",
			"data" => null
		];

		$user = User::find($id);
		if(!$user){
			$salida["msg"] = "El usuario no existe";
			return $salida;
		}

		$metas_get 		= ['user_profile_description','user_profile_address','user_profile_notes']; //add another meta 
		$metas 				= UserMeta::whereIn('key',$metas_get)->where('user_id',$id)->get();		

		$fulldata = [
			'metas' => $metas,
			'user' => $user->load('profile_img')
		];

        $salida = [
            'code' => 1,
            'data' => $fulldata,
            'msg' => 'Datos recuperados'
        ];

		return $salida;				
	}

	#Acceso publico, Obtener informacion general e imagenes de perfil 
	public function summaryInfo($id){
		$salida = [
			"code" => 0,
			"msg"=>"",
			"data" => null
		];

		$user = User::find($id);
		
		if(!$user){
			$salida["msg"] = "El usuario no existe";
			return $salida;
		}

		$tags 				= 	User::select('tg.id','tg.name')->join('tags_on_profiles AS top','top.user_id','users.id')
									->join('tags AS tg','tg.id','top.tag_id')->where('users.id',$id)->get();
		$profile_media = MediaProfile::where('user_id',$user->id)->get(); 									

        $fulldata = [
            'user' => $user,
			'tags' => $tags,
			'media_profile' => $profile_media
        ];

        $salida = [
            'code' => 1,
            'data' => $fulldata,
            'msg' => 'Datos recuperados'
        ];
		return $salida;	
	}

	public function deleteTag($id_user,$id_tag){
        $salida = [
            'code' => 0,
            'data' => null,
            'msg' => null
        ];

		if(Auth::user()->id != $id_user){
			$salida["msg"] = "Permiso denegado";
			return $salida;
		}

		$row = TagsOnProfile::where('user_id',$id_user)->where('tag_id',$id_tag)->first();
		if(!$row){
			$salida["msg"] = "No existe la relacion";
			return $salida;
		}
		
		$row->delete();
		$salida["code"] = 1;
		$salida["msg"] = "Eliminado";
		return $salida;
	}

	public function updateTags(Request $request,$id){
        $salida = [
            'code' => 0,
            'data' => null,
            'msg' => null
        ];
	
		if(Auth::user()->id != $id){
			$salida["msg"] = "Permiso denegado";
			return $salida;
		}
		
		$exists = TagsOnProfile::where('user_id',$id)->where('tag_id',$request->tag_id)->first();
		if($exists){
			$salida["code"] = 409; //ya existe 
			return $salida;
		}

		$top = new TagsOnProfile();
		$top->user_id = $id;
		$top->tag_id = $request->tag_id;

		if(!$top->save()){
			$salida = "Problemas al guardar los datos";
			return $salida;
		}

		$salida["code"] = 1;
		$salida["msg"] ="Accion completada";
		$salida["data"] = $top->tag->name;
		return $salida;
	}	


}
