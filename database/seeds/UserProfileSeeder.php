<?php
use App\UserProfile;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use database\migrations\CreateUsersGoalTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;  


class UserProfileSeeder extends Seeder
{
    public function run()
    {
        $user = new UserProfile;
        $user->user_id = 1;
        $user->gender = 'M';
        $user->birthday = '1984-12-10';
        $user->height = 171;
        $user->weight = 86;
        $user->activity_level = 2;
        $user->exercise_days = 4;
        $user->save();
    }
} 