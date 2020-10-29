<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostEvent extends Model
{
    protected $fillable = [
        'title','content','type_post','creator_id'
    ];
}
