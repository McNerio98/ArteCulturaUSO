<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
   // use HasApiTokens;
    use Notifiable;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'password',
        'is_admin',
        'username',
        'telephone',
        'status',
        'img_profile_id', //quitar el api token 
        'count_posts',
        'count_events'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile_img(){
        //Al final genera la misma query estos dos 
        //return $this->hasOne("App\MediaProfile","id2","img_profile_id");
        return $this->belongsTo("App\MediaProfile","img_profile_id","id");
    }

    //Get Only names from permissions 
    public function getCapsAttribute(){
        $ret = [];
        $list = $this->getPermissionsViaRoles();
        foreach($list as $c){
            array_push($ret,$c["name"]);
        }
        return $ret; 
    }

}
