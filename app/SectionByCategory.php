<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SectionByCategory extends Model
{
    protected $fillable = ['section_id','category_id'];
}
