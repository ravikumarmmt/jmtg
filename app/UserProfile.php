<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
 
 
class UserProfile extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_profile';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = ['user_id', 'picture', 'gender', 'birthday', 'height', 'weight', 'activity_level', 'exercise_days'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];     
}