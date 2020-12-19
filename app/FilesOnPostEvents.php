<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FilesOnPostEvents extends Model
{
    protected $fillable = [
        'id_post_event','name','path_file','','type_file'
    ];
}
