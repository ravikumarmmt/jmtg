<?php
use App\FoodPreferenceCategory;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use database\migrations\CreateUsersGoalTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;  

class FoodPreferenceCategorySeeder extends Seeder
{
    public function run()
    {
        //Goals::create(['name' =>]); 
        $data = [['name' => 'special dietary needs', 'face' => 'img/apple.png'], ['name' => 'protein', 'face' => 'img/fish.png'], ['name' => 'cereals', 'face' => 'img/sandwich.png'],
                ['name' => 'fruits and vegetables', 'face' => 'img/greeb.png'], ['name' => 'dairy', 'face' => 'img/milk.png'], ['name' => 'sweets and snacks', 'face' => 'img/ice.png'], 
                ['name' => 'condiments', 'face' => 'img/condiments.png'], ['name' => 'beverages', 'face' => 'img/tea.png'], ['name' => 'supplements', 'face' => 'img/herb.png']];
        DB::table('food_preference_category')->insert($data);
    }
} 