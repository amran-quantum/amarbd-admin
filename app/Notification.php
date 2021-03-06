<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable =[
        'user_id','msg','seen','type'
    ];
    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
}
