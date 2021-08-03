<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DtlEvent extends Model
{
    protected $fillable = [
        'event_date','frequency','has_cost','cost','event_id'
    ];

}
