<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
 
 
class Referral extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'referral';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = ['sender', 'referrer', 'credit', 'created_at', 'updated_at'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
   // protected $hidden = ['created_at', 'updated_at'];     
}