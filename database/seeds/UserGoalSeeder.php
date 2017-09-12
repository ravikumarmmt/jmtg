<?php
use App\UserGoal;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use database\migrations\CreateUsersGoalTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;  


class UserGoalSeeder extends Seeder
{
    public function run()
    {
        $usergoal = new UserGoal;
        $usergoal->user_id = 1;
        $usergoal->goals_id = 3;
        $usergoal->goal_weight = 75.00;
        $usergoal->weight_preferred_pace_id = 1;
        $usergoal->dietary_requirements_id = 3;
        $usergoal->save();
    }
} 