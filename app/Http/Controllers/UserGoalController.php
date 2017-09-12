<?php 

namespace App\Http\Controllers;

use App\UserGoal;
use App\Goals;
use App\DietryRequirements;
use App\PreferredPace;

use App\Http\Controllers\MealPlanController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 

class UserGoalController extends Controller
{
     /**
     * 
     * User Authentication used in construct method.
     * Authorizes user only to access store, show method 
     * 
     */
    public function __construct() {
        $this->middleware('auth', ['only' => ['show', 'store', 'update', 'destroy']]);
    }//End of construct

    /**
     * Method for show user goal by given input.
     *
     * @param $request
     * 
     * @return Response
     */     
    public function show(Request $request){
        $this->validateGetGoalRequest($request);
        $goals = Goals::all();
        $dietryrequirements = DietryRequirements::all();
        $preferredpace = PreferredPace::all();
        if($request->input('user_id'))
            $UserGoal = DB::table('users_goal')
                        ->join('goals', 'users_goal.goals_id', '=', 'goals.id')
                        ->join('preferred_pace', 'users_goal.weight_preferred_pace_id', '=', 'preferred_pace.id')
                
                        ->leftjoin('dietry_requirements', 'users_goal.dietary_requirements_id', '=', 'dietry_requirements.id')
                        ->where('users_goal.user_id', $request->input('user_id'))
                        ->get(['users_goal.id as id', 'users_goal.user_id as user_id', 'goals.id as goal_id', 'goals.name as goal',
                            'users_goal.goal_weight as goalweight', 'preferred_pace.id as preferred_pace_id', 'preferred_pace.name as preferred_pace',
                            'dietry_requirements.id as dietry_requirements_id', 'dietry_requirements.name as dietry_requirements']);
            $UserGoal = count($UserGoal) > 0 ? $UserGoal[0]: $UserGoal;
        if(count($UserGoal)>0)
            return response()->json(['status' => 1, 'message' => '', 'isupdated' => 1, 'goals' => $goals,
                                 'dietryrequirements' => $dietryrequirements, 'preferredpace' => $preferredpace, 'data' => $UserGoal]);           
        return response()->json(['status' => 1, 'message' => '', 'isupdated' => 0, 'goals' => $goals,
                                 'dietryrequirements' => $dietryrequirements, 'preferredpace' => $preferredpace, 'data' => $UserGoal]);
    }//End of show 
    
    /**
     * Method for store new user goal.
     *
     * @param $request
     * 
     * @return Response
     */    
    public function store(Request $request){
        $this->validateRequest($request);
        $usergoal = UserGoal::where('user_id', $request->input('user_id'))->first();
        if(!$usergoal){
            $usergoal = UserGoal::create([ 
                                    'user_id' => $request->input('user_id'),
                                    'goals_id' => $request->input('goals_id'),
                                    'goal_weight' => $request->input('goal_weight'),
                                    'weight_preferred_pace_id'=>  $request->input('weight_preferred_pace_id'),
                                    'dietary_requirements_id'=>  $request->input('dietary_requirements_id'),
                                ]);
            if($usergoal)
                //MealPlanController::sendMealPlanToAlgorithm($request->input('user_id'));
                return response()->json(['status' => 1, 'message' => 'Successfully usergoal has been created', 'data' => $usergoal]);
            return response()->json(['status' => 1, 'message' => 'usergoal was not created'], 422);
        } else {
            return response()->json(['status' => 1, 'data' => $usergoal]);
        }    
        
    }//End of store
        
    /**
     * Method for Update user goal by given id.
     *
     * @param int $id
     * @param $request
     * 
     * @return Response
     */  
    public function update(Request $request, $id){
        $users_goal = UserGoal::find($id);
        if(!$users_goal)
            return response()->json(['status' => 0,'data' => "The user goal with id {$id} doesn't exist"]);
        $this->validateRequest($request);
        $users_goal->user_id = $request->input('user_id');
        $users_goal->goals_id = $request->input('goals_id');
        $users_goal->goal_weight = $request->input('goal_weight');
        $users_goal->weight_preferred_pace_id = $request->input('weight_preferred_pace_id');
        $users_goal->dietary_requirements_id = $request->input('dietary_requirements_id');
        $users_goal->save();
        return response()->json(['status' => 1, 'data' => "The users goal with with id {$users_goal->id} has been updated"]);
    }//End of update  
    
    /**
     * Method for delete user goal by given id.
     *
     * @param int $id
     * 
     * @return Response
     */  
    public function destroy($id){
            $users_goal = UserGoal::find($id);
            if(!$users_goal)
               return response()->json(['status' => 0,'data' => "The user goal with id {$id} doesn't exist"]);
            $users_goal->delete();
            return response()->json(['status' => 1, 'data' => "The user goal with id {$id} has been deleted"]);
    }//End of destroy    
    
    /**
     * Method for validate user Goal inputs.
     *
     * @param $request
     * 
     * @return Response
     */  
    public function validateRequest(Request $request){
        $rules = [
                'goals_id' => 'required',
                'goal_weight' => 'required', 
                'weight_preferred_pace_id' => 'required',
                'dietary_requirements_id' => 'required'
            ];
        $this->validate($request, $rules);
    }//End of validateRequest
    
    /**
     * Method for validate GetGoal Request .
     *
     * @param $request
     * 
     * @return Response
     */    
    public function validateGetGoalRequest(Request $request){
        $rules = [
                'user_id' => 'required|numeric',
            ];
        $this->validate($request, $rules);
    }//End of validateRequest 
}


