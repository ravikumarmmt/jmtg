<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
 
 
class PreferredPace extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'preferred_pace';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];  
}