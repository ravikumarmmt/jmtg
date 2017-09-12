<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
 
 
class DietaryExclusions extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dietary_exclusions';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];  
}


