<?php
namespace App\Http\Controllers;

use App\DietaryExclusions;
use App\UserProfile;
use App\UserMealPlan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 

class MealPlanController extends Controller
{
    /**
     * 
     * User Authentication used in construct method.
     * Authorizes user only to access store, show method 
     * 
    */
    public function __construct() {
       // $this->middleware('auth', ['only' => ['getMealPlan']]);
    }//End of construct 
    
    /**
     * Method for get meal plan.
     *
     * @param $request
     * 
     * @return Response
     */     
    public function getMealPlan(Request $request){ 
        $UserMealPlan = UserMealPlan::where('user_id', $request->input('user_id'))
                                        ->where('plan_type', $request->input('plan_type')) 
                                        ->whereRaw("DATE(created_at) <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)")
                                        ->orderBY('created_at', 'desc')->get()->toArray();
  
        if(count($UserMealPlan) > 0){
            $data['id'] = $UserMealPlan[0]['id'];
            $data['user_id'] = $UserMealPlan[0]['user_id'];
            $data['mealplan'] = json_decode($UserMealPlan[0]['mealplan'], true);
            $data['plan_type'] = $UserMealPlan[0]['plan_type'];
            $data['created_at'] = $UserMealPlan[0]['created_at'];
            $data['updated_at'] = $UserMealPlan[0]['updated_at'];            
            return response()->json(['status' => 1, 'message' => 'User Meal Plan',  'data' => $data]);
        } else {
           return $this->sendMealPlanToRequest($request->input('user_id'), $request->input('plan_type'));
        }
    }//End of getMealPlan  
    
    /**
     * Method to get meal plan from python algorithm
     * after getting response. Save it to database and return response
     *
     * @param int $user_id
     * @param json $plantype
     * 
     * @return Json Response
     */     
    public function sendMealPlanToRequest($user_id, $plantype){
        $send_mealPlan = DB::table('users_profile')
                            ->join('users_goal', 'users_profile.user_id', '=', 'users_goal.user_id')
                            ->join('current_activity_level', 'users_profile.activity_level', '=', 'current_activity_level.id')
                            ->join('goals', 'users_goal.goals_id', '=', 'goals.id')
                            ->where('users_profile.user_id', $user_id)
                            ->get(['users_profile.weight as weight', 'users_goal.goal_weight as goal_weight', 
                                    DB::raw(" CASE
                                        WHEN goals.name = 'Weight Loss' THEN 'loss'
                                        WHEN goals.name = 'Weight Maintenance' THEN 'maintenance'
                                        WHEN goals.name = 'Weight Gain' THEN 'gain'
                                        END
                                        as goal"),
                                    DB::raw("FLOOR(DATEDIFF(CURRENT_TIMESTAMP(),users_profile.birthday)/365.5) as age"),
                                    DB::raw(" CASE 
                                            WHEN users_profile.gender = 'M' THEN 'male'
                                            WHEN users_profile.gender = 'F' THEN 'female'
                                            END
                                            as gender"),
                                    'users_profile.height as height', 'users_profile.exercise_days as exercise_days',
                                    'current_activity_level.name as activity_level']);
        $send_mealPlan = json_decode($send_mealPlan, true);
        if(count($send_mealPlan)==0)
            return response()->json(['status' => 1, 'message' => 'No Data'], 422);
        $send_mealPlan = $send_mealPlan[0];
        $send_mealPlan['plan_type'] = $plantype;
        $params = json_encode($send_mealPlan, JSON_NUMERIC_CHECK);
        
        //$url = 'http://104.199.228.194:5000/meal_plan';
        $url = 'http://104.199.228.194:4761/meal_plan';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));                                                                        
        $response = curl_exec($ch);
        //echo "<pre>";print_r($response);die;
        curl_close($ch);
        $response = json_decode($response, true);
        
        if($response)
            return $this->store($response, $user_id, $plantype);
        return response()->json(['status' => 1, 'message' => 'OOPS! Something went wrong.'], 422);
   }//End of show   
    
    /**
     * Method to save meal plan information in database
     *
     * @param int $user_id
     * @param json $response
     * @param int $plantype 
     * 
     * @return Json Response
     */    
    public function store($response, $user_id, $plantype){
        $response = json_encode($response);
        $UserMealPlan = UserMealPlan::create([   
                            'user_id' => $user_id,
                            'mealplan' => $response,
                            'plan_type' => $plantype
                        ]);
        $UserMealPlan = json_decode($UserMealPlan, true);
        if($UserMealPlan){
            $data['id'] = $UserMealPlan['id'];
            $data['user_id'] = $UserMealPlan['user_id'];
            $data['mealplan'] = json_decode($UserMealPlan['mealplan'], true);
            $data['plan_type'] = $UserMealPlan['plan_type'];
            $data['created_at'] = $UserMealPlan['created_at'];
            $data['updated_at'] = $UserMealPlan['updated_at'];             
            return response()->json(['status' => 1, 'message' => 'Meal Plan has been created',  'data' => $data]);        
        } else {    
            return response()->json(['status' => 1, 'message' => 'OOPS! Something went wrong.'], 422);
        }
        
    }//End of store   
    
}//End of MealPlanController   
