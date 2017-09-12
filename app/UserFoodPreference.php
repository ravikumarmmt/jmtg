<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
 
 
class UserFoodPreference extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_food_preference';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = ['user_id', 'category_id', 'subcategory_id', 'checked'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];     
}