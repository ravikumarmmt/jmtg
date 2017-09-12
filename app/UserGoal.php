<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
 
 
class UserGoal extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_goal';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = ['user_id', 'goals_id', 'goal_weight', 'weight_preferred_pace_id', 'dietary_requirements_id'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];     
}