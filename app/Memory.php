<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Memory extends Model
{

    public function media(){
        return $this->hasMany('App\FilesOnMemory');
    }

    public function presentation_model(){
        return $this->belongsTo("App\FilesOnMemory","presentation_img","id");
    }

    //
}
