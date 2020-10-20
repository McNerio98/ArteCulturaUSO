<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
	public function __construct(){
		$this->middleware('auth');
	}

    public function index(){
		//Cargar toda la informacion 
    	return [info => 'ready'];
	}
	

}
