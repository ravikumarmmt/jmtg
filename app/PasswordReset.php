<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
 
 
class PasswordReset extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'password_resets';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = ['email', 'token', 'created_at', 'updated_at'];
//    /**
//     * The attributes excluded from the model's JSON form.
//     *
//     * @var array
//     */
//    protected $hidden = ['created_at', 'updated_at'];     
}

