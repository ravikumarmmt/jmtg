<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
 
 
class Goals extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'goals';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];  
}
