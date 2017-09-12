<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
 
 
class CurrentActivityLevel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'current_activity_level';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];    
  
}