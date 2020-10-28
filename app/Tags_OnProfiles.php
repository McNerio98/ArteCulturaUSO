<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tags_OnProfiles extends Model
{
    //
    protected $fillable = [
        'user_id','tag_id'
    ];
}
