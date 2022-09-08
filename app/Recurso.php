<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recurso extends Model
{
    public function media(){
        return $this->hasMany('App\FilesOnResource');
    }

    public function presentation_model(){
        return $this->belongsTo("App\FilesOnResource","presentation_img","id");
    }    
}
