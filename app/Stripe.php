<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
 
 
class Stripe extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stripe_payment';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = ['card_no', 'name', 'last4', 'type', 'amount', 'currency', 'result', 'created_at', 'updated_at'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
   // protected $hidden = ['created_at', 'updated_at'];     
}