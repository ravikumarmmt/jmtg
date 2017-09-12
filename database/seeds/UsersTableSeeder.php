<?php
use App\User;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use database\migrations\CreateUsersGoalTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;  


class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $api_token = str_random(60);
        $results = DB::select(DB::raw('SELECT NOW() AS end_time'));
        $user = new User;
        $user->name = 'ravi';
        $user->email = 'ravik@enqos.com';
        $user->password = Crypt::encrypt('test@123');
        $user->api_token = $api_token;
        $user->expire_at = $results[0]->end_time;
        $user->save();
    }
} 
