<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $per_page = ($request->per_page === null)?6:$request->per_page;
        
        $valid_range = array(6,12,24);
        if(! in_array($per_page,$valid_range)){
            $per_page = 6;
        }

        $result = DB::table('users')
        ->join("model_has_roles","model_has_roles.model_id","=","users.id")
        ->join("roles","roles.id","=","model_has_roles.role_id")
        ->select("users.id","roles.name as role","users.name","users.img-profile","users.email",
            "users.username","users.telephone","users.rubros","users.status")
        ->paginate($per_page);

        return [
            'paginate' =>[  
                    'total' =>$result->total(),
                    'current_page'  => $result->currentPage(),
                    'per_page'      => $result->perPage(),
                    'last_page'     => $result->lastPage(),
                    'from'          => $result->firstItem(),
                    'to'            => $result->lastPage(),                    
            ],
            'users' => $result

        ];
        return $users;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
