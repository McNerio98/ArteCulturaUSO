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
use Storage;

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

		if($user->active == false || $user->is_admin == true || $user->status == 'disabled'){
			return redirect()->route('inicio');
		}
		
		return view("profile.index",['id_user_cur' => $id]);
	}




	public function elements(Request $request, $id){
		$output = [
			"code" => 0,
			"msg"=>"",
			"data" => null,
			'pagination' => null
		];		

		$user = User::find($id);
		if(!$user){
			$output["msg"] = "Usuario no existe";
			return $output;			
		}

		if($user->active == false || $user->status == 'disabled'){
			$output["msg"] = "Cuenta no disponible";
			return $output;
		}		

		$per_page = ($request->per_page === null || $request->per_page > 15) ? 15 : $request->per_page;

		$result = PostEvent::where('creator_id',$id)
			->with('media')
			->with('owner')
			->with('event_detail')
			->orderBy('id','desc')
			->paginate($per_page);
		
		$items = [];
		foreach($result->items() as $el){
			$el->owner->load('profile_img');
			$items[] = $el;
		}
		
        $output["pagination"] = [
            'total' 				=>$result->total(),
            'current_page'  => $result->currentPage(),
            'per_page'      => $result->perPage(),
            'last_page'     => $result->lastPage(),
            'from'          => $result->firstItem(),
            'to'            => $result->lastPage(),
        ];

        $output["data"] = $items;
        $output["code"] = 1;
        return $output;				
	}

	#Carga la informacion completa del usuario
	public function information($id){
		$output = [
			"code" => 0,
			"msg"=>"",
			"data" => null
		];

		$user = User::find($id);
		if(!$user){
			$output["msg"] = "El usuario no existe";
			return $output;
		}

		if($user->active == false || $user->is_admin == true  || $user->status == 'disabled'){
			$output["msg"] = "Cuenta no disponible";
			return $output;
		}		

		$metas_get 		= ['user_profile_description','user_profile_address','user_profile_notes']; //add another meta 
		$metas 				= UserMeta::whereIn('key',$metas_get)->where('user_id',$id)->get();		
		$tags 				= 	User::select('tg.id','tg.name')->join('tags_on_profiles AS top','top.user_id','users.id')
									->join('tags AS tg','tg.id','top.tag_id')->where('users.id',$id)->get();
		$profile_media = MediaProfile::where('user_id',$user->id)->get(); 	
		$user->load('profile_img');

		$fulldata = [
			'metas' => $metas,
			'user' => $user,
			'tags' => $tags,
			'media_profile' => $profile_media
		];

        $output = [
            'code' => 1,
            'data' => $fulldata,
            'msg' => 'Datos recuperados'
        ];

		return $output;				
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
