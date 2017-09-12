<?php
use App\Goals;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use database\migrations\CreateUsersGoalTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;  

class GoalsTableSeeder extends Seeder
{
    public function run()
    {
        //Goals::create(['name' =>]); 
        $data = [['name' => 'Weight Loss'],['name' => 'Weight Gain'], ['name' => 'Weight Maintenance'], ['name' => 'Weight Gain']]; 
        DB::table('goals')->insert($data);
    }
} 
