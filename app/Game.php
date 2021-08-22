<?php
namespace App;
use DB;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'title','description','age_range','cover_photo','updated_at','created_at'
    ];
    public function total_questions(){
        return $this->hasOne('App\Question','game_id')->select('game_id',DB::raw('count(game_id) as total_questions'))->groupBy('game_id');
    }
}
