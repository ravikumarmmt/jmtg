<?php
use App\PreferredPace;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use database\migrations\CreateUsersGoalTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;  

class PreferredPaceSeeder extends Seeder
{
    public function run()
    {
        $data = [['name' => 'Recommended'],['name' => 'Rapid']]; 
        DB::table('preferred_pace')->insert($data);
    }
} 
