<?php
use App\Mtgplan;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use database\migrations\CreateUsersGoalTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;  


class MtgplanSeeder extends Seeder
{
    public function run()
    {
        
        $data = [['name' => 'free', 'amount' => 0, 'level' => 0, 'validity' => 7], ['name' => 'plan_1', 'amount' => 100, 'level' => 1, 'validity' => 30], 
            ['name' => 'plan_2', 'amount' => 200, 'level' => 2, 'validity' => 30], ['name' => 'plan_3', 'amount' => 300, 'level' => 3, 'validity' => 30]]; 
        DB::table('mtg_plan')->insert($data);
 
    }
} 