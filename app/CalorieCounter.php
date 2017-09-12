<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
 
 
class CalorieCounter extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'calorie_counter';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = ['user_id', 'mealtype', 'mealid', 'mealname', 'mealdata', 'date', 'created_at', 'updated_at'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];     
}