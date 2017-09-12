<?php 

namespace App\Http\Controllers;

use App\User;
use App\UserProfile;
use App\CurrentActivityLevel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;  
use Auth;

class UserProfileController extends Controller
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
     * Method for store new user profile.
     *
     * @param $request
     * 
     * @return Response
     */    
    public function store(Request $request){
        $this->validateRequest($request);
        $userprofile = UserProfile::where('user_id', $request->input('user_id'))->first();
        if(!$userprofile){
            $userprofile = UserProfile::create([ 
                                    'user_id' => $request->input('user_id'),
                                    'gender' => $request->input('gender'),
                                    'birthday' => $request->input('birthday'),
                                    'height'=>  $request->input('height'),
                                    'weight'=>  $request->input('weight'),
                                    'activity_level'=>  $request->input('activity_level'),
                                    'exercise_days'=>  $request->input('exercise_days'),
                                ]);
            if($userprofile)
                return response()->json(['status' => 1, 'message' => 'Successfully userprofile has been created', 'data' => $userprofile]);
            return response()->json(['status' => 1, 'message' => 'userprofile was not created'], 422);
        } else {
            return response()->json(['status' => 1, 'data' => $userprofile]);
        }

    }//End of store
    
    /**
     * Method for show user profile by given input.
     *
     * @param $request
     * 
     * @return Response
     */     
    public function show(Request $request){
        //$userprofile = UserProfile::where('user_id', $request->input('user_id'))->first();
        $userprofile = DB::table('users_profile')
                        ->join('users', 'users_profile.user_id', '=', 'users.id')
                        ->where('users_profile.user_id', $request->input('user_id'))
                        ->get(['users.email as email', 'users_profile.id as id', 'users_profile.user_id as user_id',
                                'users_profile.picture as picture', 'users_profile.gender as gender', 'users_profile.birthday as birthday',
                                'users_profile.height as height', 'users_profile.weight as weight', 'users_profile.activity_level as activity_level',
                                'users_profile.exercise_days as exercise_days']);
        $currentactivitylevel = CurrentActivityLevel::all();
        if($userprofile)
            return response()->json(['status' => 1, 'message' => '', 'data' => $userprofile, 'isupdated' => 1, 'currentactivitylevel' => $currentactivitylevel]);
        return response()->json(['status' => 1,  'message' => "The user profile doesn't exist",  'isupdated' => 0, 'currentactivitylevel' => $currentactivitylevel]);
    }//End of show  
    
    
    /**
     * Method for delete user profile by given id.
     *
     * @param int $id
     * 
     * @return Response
     */  
    public function destroy($id){
            $userprofile = UserProfile::find($id);
            if(!$userprofile)
               return response()->json(['status' => 0,'data' => "The user profile with id {$id} doesn't exist"]);
            $userprofile->delete();
            return response()->json(['status' => 1, 'data' => "The user profile with with id {$id} has been deleted"]);
    }//End of destroy    
    
    /**
     * Method for Update user profile by given id.
     *
     * @param int $id
     * @param $request
     * 
     * @return Response
     */  
    public function update(Request $request, $id){
        $user_profile = UserProfile::find($id);
        if(!$user_profile)
            return response()->json(['status' => 0,'data' => "The user profile with id {$id} doesn't exist"]);
        $this->validateRequest($request);
        $user_profile->user_id = $request->input('user_id');
        $user_profile->gender = $request->input('gender');
        $user_profile->birthday = $request->input('birthday');
        $user_profile->height = $request->input('height');
        $user_profile->weight = $request->input('weight');
        $user_profile->activity_level = $request->input('activity_level');
        $user_profile->exercise_days = $request->input('exercise_days');
        $user_profile->save();
        return response()->json(['status' => 1, 'data' => "The users goal with with id {$user_profile->id} has been updated"]);
    }//End of update 
    
    /**
     * Method for validate user profile inputs.
     *
     * @param $request
     * 
     * @return Response
     */  
    public function validateRequest(Request $request){
        $rules = [
                'gender' => 'required',
                'birthday' => 'required', 
                'height' => 'required',
                'weight' => 'required',
                'activity_level' => 'required',
                'exercise_days' => 'required'
            ];
        $this->validate($request, $rules);
    }//End of validateRequest
    
}
