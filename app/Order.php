<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
 
 
class Order extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = ['user_id', 'transaction_id', 'amount', 'currency', 'status', 'created_at', 'updated_at'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
   // protected $hidden = ['created_at', 'updated_at'];     
}