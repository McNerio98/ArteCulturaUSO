<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\UsersHelper;

class ProcesosController extends Controller
{
    public function index(){
        $request_users = UsersHelper::usersRequest();
        return view('procesos' , ['ac_option' =>'procesos' , 'request_users' => $request_users]);        
    }
}
