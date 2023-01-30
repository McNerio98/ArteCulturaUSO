<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Category;
use App\UserMeta;
use App\Popular;
use App\Promotion;

class WebsiteController extends Controller
{

    
    public function welcome(){
        $promos = Promotion::all();
        $cats = Category::orderBy('id')->limit(4)->get();
        return view("welcome",['promos'=>$promos,'some_categories'=>$cats,'ac_option' => 'welcome']);
    }

    public function nearby(){
        return view("nearby");
    }

    public function artistas(){
        return view("talents");
    }

    public function recursos(){
        return view("recursos.index",['ac_option' => 'resources']);
    }

    public function biografias(){
        return view("memories.index",['ac_option' => 'memories']);
    }

    public function acercade(){
        return view("about",['ac_option' => 'about']);
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
