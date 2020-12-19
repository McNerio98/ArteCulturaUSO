<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class postsEventsMeta extends Model
{
    protected $fillable = [
        'post_event_id','key','value'
    ];
}
