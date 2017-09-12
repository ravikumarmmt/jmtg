<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class FoodPreferenceCategory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'food_preference_category';
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];
    
    /**
     * Get the FoodPreferenceSubCategory record associated with the FoodPreferenceCategory.
     */
    public function FoodPreferenceSubCategory(){
        return $this->hasMany('App\FoodPreferenceSubCategory', 'parent_id');
   }
    
    
}// End Of FoodPreferenceCategory Model

