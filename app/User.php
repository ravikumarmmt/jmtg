<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

use Session;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    public static $userId; 
    public static $api_token ;
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    protected $fillable = [
//        'name', 'email', 'password',
//    ];
    protected $fillable = [
        'name', 'email', 'password', 'api_token', 'referral_code', 'expire_at'
    ];    

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'expire_at', 'created_at', 'updated_at',
    ];
    
    public static function setUser($userId = NULL, $usertoken = NULL){
        Session::insert(['key' => 'id', 'value' => 'asdas2313%&8']);
    }
    
    public static function getUser(){
        
        $data['user_id'] = self::$userId;
        $data['api_token'] = self::$api_token;        
        
        return $data;
    }    

}
