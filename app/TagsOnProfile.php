<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TagsOnProfile extends Model
{
    //
    protected $fillable = [
        'user_id','tag_id'
    ];

    public function tag()
    {
        return $this->belongsTo('App\Tag');
    }

}
