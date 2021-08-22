<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{

    protected $fillable =[
        'post_description','bn_post_description', 'created_at','updated_at'
    ];
}
