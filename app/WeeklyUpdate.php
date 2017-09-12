<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
 
 
class WeeklyUpdate extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'weekly_update';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = ['user_id', 'weight', 'arm', 'waist', 'hips', 'thighs', 'adherence', 'menstrual_cycle', 'image'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
   // protected $hidden = ['created_at', 'updated_at'];     
}
