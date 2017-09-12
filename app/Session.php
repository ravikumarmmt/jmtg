<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
 
 
class Session extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'session';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = ['key', 'value', 'expiration'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
   // protected $hidden = ['created_at', 'updated_at'];     
}