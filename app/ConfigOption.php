<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConfigOption extends Model
{
    public static function getOption($optionKey){
        $row = self::where('option_name',$optionKey)->first();
        return !is_null($row) ? $row->option_value : null;
    }
}
