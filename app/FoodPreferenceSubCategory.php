<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class FoodPreferenceSubCategory extends Model
{
    /**
     * The table associated with the model. 
     *
     * @var string
     */
     protected $table = 'food_preference_subcategory';
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];   
    /**
     * Get the post that owns the comment.
     * 
     */
    public function FoodPreferenceCategory(){
        return $this->belongsTo('App\FoodPreferenceCategory');
    }
    
}// End Of class FoodPreferenceSubCategory Model
 
