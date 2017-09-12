<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
 
 
class OrderDetails extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_details';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = ['order_id', 'user_id', 'plan_type', 'amount', 'balance_amount','validity', 'active', 'date', 'created_at', 'updated_at'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
   // protected $hidden = ['created_at', 'updated_at'];     
}