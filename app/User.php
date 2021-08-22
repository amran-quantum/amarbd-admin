<?php

namespace App;
use DB;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Tymon\JWTAuth\Contracts\JWTSubject;
// use JWTAuth;



class User extends Authenticatable
{
    use Notifiable;



    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fullName','userType','email', 'password','profilePic','address','userType','app_token','language'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];


    public function dashboard(){
        return $this->hasMany('App\Dashboard','user_id');
    }
    public function game(){
        return $this->hasOne('App\Game','game_id');
    }

    // public function sub(){
    //     return $this->hasMany('App\Subscription','user_id');
    // }
    public function badge(){
        return $this->hasMany('App\Badge','user_id');
    }
    public function member(){
        return $this->hasMany('App\Membership','user_id');
    }

    public function location(){
        return $this->belongsTo('App\Location','location_id');
    }
    public function total_badge(){
        return $this->hasOne('App\Badge','user_id')->select('user_id',DB::raw('sum(badge) as total_badge'))->where('badge','!=',0)->groupBy('user_id');
    }
    public function total_badge_t(){
        return $this->hasOne('App\Badge','user_id')->select('user_id',DB::raw('sum(badge) as total_badge'))->where('badge','!=',0)->whereDate('created_at',date('Y-m-d'))->groupBy('user_id');
    }
    public function total_badge_m(){
        return $this->hasOne('App\Badge','user_id')->select('user_id',DB::raw('sum(badge) as total_badge'))->where('badge','!=',0)->whereYear('created_at',date('Y'))->whereMonth('created_at',date('m'))->groupBy('user_id');
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



}
