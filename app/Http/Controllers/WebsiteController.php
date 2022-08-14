<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Category;
use App\UserMeta;
use App\Popular;

class WebsiteController extends Controller
{

    public function welcome(){

        $data = Popular::join('post_events','post_events.id','=','populars.post_event_id')
        ->leftJoin('files_on_post_events AS fope','fope.id','=','post_events.presentation_img')
        ->select('post_events.id','post_events.title',DB::raw('CONCAT(substring(post_events.content,1,100),\'...\') AS content'),'post_events.type_post',
        'post_events.is_popular','fope.name','fope.type_file')->where('post_events.status','approved')->get();        
        
        $cats = Category::orderBy('id')->limit(4)->get();

        return view("welcome",['posts_popular'=>$data,'some_categories'=>$cats]);
    }

    public function nearby(){
        return view("nearby");
    }

    public function artistas(){
        return view("talents");
    }

    public function promotores(){
        return view("promotores");
    }

    public function escuelas(){
        return view("escuelas");
    }

    public function recursos(){
        return view("resources");
    }

    public function biografias(){
        return view("biographies");
    }
    
    public function homenajes(){
        return view("memories");
    }    

    public function acercade(){
        return view("about");
    }

    public function events(){
        return view("events");
    }

    public function accountRequest($user_name,$code){
        return view('request-status',['user_name'=>$user_name,'code_status' => $code]);
    }

    public function checkEmail($email){
        return view('verify-email-pending',['user_email' => $email]);
    }
}
