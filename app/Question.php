<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'game_id','question','options','right_answer','updated_at','created_at'
    ];
    public function game(){
        return $this->belongsTo('App\Game','game_id');
    }
}
