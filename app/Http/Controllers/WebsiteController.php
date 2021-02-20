<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Category;
use App\UserMeta;

class WebsiteController extends Controller
{

    public function welcome(){

        $query = "select pe.id,pe.title,concat(substring(pe.content,1,100),'...') as content,pe.type_post,pe.is_popular,fop.name,fop.path_file,fop.type_file from post_events pe
        left join (select * from files_on_post_events fope where fope.type_file = 'image' group by fope.id_post_event) as fop on fop.id_post_event = pe.id
        where pe.is_popular = true";
        
        $data = DB::select(DB::raw($query));
        $cats = Category::orderBy('id')->limit(4)->get();

        return view("welcome",['posts_popular'=>$data,'some_categories'=>$cats]);
    }

    public function artistas(){
        return view("artitas");
    }

    public function promotores(){
        return view("promotores");
    }

    public function escuelas(){
        return view("escuelas");
    }

    public function recursos(){
        return view("recursos");
    }

    public function biografias(){
        return view("biografias");
    }
    
    public function homenajes(){
        return view("homenajes");
    }    

    public function acercade(){
        return view("acercade");
    }

    //este es el metodo primero 
    public function profile(){
        $current_user = Auth::user();
        $description = UserMeta::select("value")->where('key','user_profile_description')->where('user_id',Auth::user()->id)->first();
        
        return view("profile",['current_user'=>$current_user,'user_description'=>$description]);
    }

    public function events(){
        return view("events");
    }
}
