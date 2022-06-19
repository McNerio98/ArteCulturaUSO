<?php

namespace App\Helper;
use App\User;

class UsersHelper{
    public static function usersRequest(){
        return User::with("profile_img")
            ->where("status","request")
            ->where('email_verified_at','<>',null)->get();        
    }
}

?>