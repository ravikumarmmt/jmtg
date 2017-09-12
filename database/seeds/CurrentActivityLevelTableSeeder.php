<?php
use App\CurrentActivityLevel;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use database\migrations\CreateUsersGoalTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;  

class CurrentActivityLevelTableSeeder extends Seeder
{
    public function run()
    {
        //Goals::create(['name' =>]);
        $data = [['name' => '1 - Couch potato'],['name' => '1 - couch carrot'], ['name' => 2], ['name' => 3], ['name' => 4], ['name' => '5 - Retail Assistant']
                , ['name' => 6],['name' => 7], ['name' => 8], ['name' => 9], ['name' => '10 - Labourer']];
        DB::table('current_activity_level')->insert($data);
    }
} 
