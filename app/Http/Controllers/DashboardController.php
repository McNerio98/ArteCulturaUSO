<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{

	public function __construct(){
		$this->middleware('auth');
	}

    public function index(){
    	return view('admin.home');
    }

    public function users(){
    	return view('admin.users');
    }
}
