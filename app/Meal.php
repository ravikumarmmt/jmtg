<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
 
 
class Meal extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meal';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = ['user_id', 'name', 'food_list', 'created_at', 'updated_at'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
   // protected $hidden = ['created_at', 'updated_at'];     
}