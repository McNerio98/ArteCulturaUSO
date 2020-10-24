<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    public function profile(){
        return view("profile");
    }

    public function events(){
        return view("events");
    }
}
