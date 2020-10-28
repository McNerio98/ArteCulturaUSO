<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'artistic_name','content_desc','rubros','count_posts','count_evebts','user_id'
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }
}
