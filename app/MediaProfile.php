<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MediaProfile extends Model
{
    protected $fillable = [
        'user_id','path_file'
    ];    
}
