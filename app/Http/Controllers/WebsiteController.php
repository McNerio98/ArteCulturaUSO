<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebsiteController extends Controller
{
    public function index(){
        return view("welcome");
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

    public function acercade(){
        return view("acercade");
    }

    //este es el metodo primero 
    public function profile(){
        $current_user = Auth::user();
        return view("profile",compact('current_user'));
    }

    public function events(){
        return view("events");
    }
}
