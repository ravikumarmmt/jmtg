<?php
use Illuminate\Database\Seeder;


use Database\seeds\UsersTableSeeder;
use Database\seeds\CurrentActivityLevelTableSeeder;
use Database\seeds\GoalsTableSeeder;
use Database\seeds\UserProfileSeeder;
use Database\seeds\UserGoalSeeder;
use Database\seeds\FoodPreferenceCategorySeeder;
use Database\seeds\FoodPreferenceSubCategorySeeder;
use Database\seeds\PreferredPaceSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call('UsersTableSeeder');
        
        $this->call('UsersTableSeeder');
        $this->call('CurrentActivityLevelTableSeeder');
        $this->call('GoalsTableSeeder');
        $this->call('UserProfileSeeder');
        $this->call('UserGoalSeeder');
        $this->call('FoodPreferenceCategorySeeder');
        $this->call('FoodPreferenceSubCategorySeeder');
        $this->call('PreferredPaceSeeder');
        $this->call('MtgplanSeeder');
        
        
    }
}
