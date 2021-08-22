<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'headline','news', 'bn_headline','bn_news','banner','user_id','district','division','bn_district','bn_division', 'publish_status','view','category','bn_category','shareable_link','video_link'
    ];
    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
}
