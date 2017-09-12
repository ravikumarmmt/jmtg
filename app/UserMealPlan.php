<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
 
 
class UserMealPlan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_meal_plan';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'mealplan', 'plan_type', 'created_at', 'updated_at'
    ];    

//    /**
//     * The attributes excluded from the model's JSON form.
//     *
//     * @var array
//     */
//    protected $hidden = [
//        'password', 'expire_at', 'created_at', 'updated_at',
//    ];

  
}