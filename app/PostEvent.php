<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostEvent extends Model
{
    protected $fillable = [
        'title','content','type_post','creator_id'
    ];

    public function media(){
        return $this->hasMany("App\FilesOnPostEvents",'id_post_event');
    }

    #Si es de tipo event debe etener 
    public function event_detail(){
        return $this->hasOne('App\DtlEvent','event_id');
    }

    #Significa que desde el modelo de PostEvent puede alcanzar al creador del elemento
    #la tabla de usuarios no tiene ninguna clave relacionada en su modelo, pero se hara match 
    #usando el id del usuario con la clave llamada creator_id 
    public function owner(){
        return $this->belongsTo("App\User","creator_id");
    }

    public function presentation_model(){
        return $this->belongsTo('App\FilesOnPostEvents','presentation_img','id');
    }
}
