<?php 

namespace App\Http\Controllers;

use App\User;
use App\UserProfile;
use App\UserGoal;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;  
use Auth;


class UserController extends Controller
{

    /**
     * 
     * User Authentication used in construct method.
     * Authorizes user only to access sampleData method 
     * 
     */    
    public function __construct(User $user) {
        $this->user = $user;
        $this->middleware('auth', ['only' => ['sampleData']]);
    }//End of construct
    
    /**
     * Method for list all Users.
     *
     *@return Response
     */
    public function index(){
        $users = $this->user->all();
        if($users)
            return response()->json(['status' => 1, 'message' => 'some msg', 'data' => $users]);
        return response()->json(['status' => 0, 'message' => 'No User'], 404);
    }//End of index
    
    /**
     * Method for create new user.
     *
     * @param $request
     * 
     * @return Response
     */    
    public function store(Request $request){
        
        $this->validateRequest($request);
        $api_token = str_random(60);
        $results = DB::select(DB::raw('SELECT NOW() AS end_time'));
        $user = $this->user->create([  'name' => $request->input('name'),
                                'email' => $request->input('email'),
                                'password'=>  Crypt::encrypt($request->input('password')),
                                'api_token' => $api_token,
                                'referral_code' => $this->uniqueId(),
                                'expire_at' => $results[0]->end_time
                            ]);
        if($user)
            return response()->json(['status' => 1, 'message' => 'Successfully user has been created', 'data' => $user]);
        return response()->json(['status' => 0, 'message' => 'user not register'], 422);
    }//End of store
    
    public function uniqueId(){
        return $uniqueId = str_random(2).substr(rand(1000, 1000000000), 2, 2).str_random(3);
    }
 
    /**
     * Method for update user by given input.
     *
     * @param int $id
     * 
     * @return Response
     */     
    public function show($id){
        $user = $this->user->find($id);
        if($user)
            return response()->json(['status' => 1, 'data' => $user]);
        return response()->json(['status' => 0,'data' => "The user with id {$id} doesn't exist"]);
    }//End of show    
    
    /**
     * Method for Update user by given id.
     *
     * @param int $id
     * @param $request
     * 
     * @return Response
     */  
    public function update(Request $request, $id){
        $user = $this->user->find($id);
        if(!$user)
            return response()->json(['status' => 0,'data' => "The user with id {$id} doesn't exist"]);
        $this->validateRequest($request);
        $user->name = $request->input('name');
        $user->email 	= $request->input('email');
        $user->password = Crypt::encrypt($request->input('password'));
        $user->save();
        return response()->json(['status' => 1, 'data' => "The user with with id {$user->id} has been updated"]);
    }//End of update  
    
    /**
     * Method for delete user by given id.
     *
     * @param int $id
     * 
     * @return Response
     */  
    public function destroy($id){
            $user = $this->user->find($id);
            if(!$user)
               return response()->json(['status' => 0,'data' => "The user with id {$id} doesn't exist"]);
            $user->delete();
            return response()->json(['status' => 1, 'data' => "The user with with id {$id} has been deleted"]);
    }//End of destroy

    /**
    *Method for user login
    * 
    * @param string $email
    * @param string $password
    * 
    * @return Response
    */
    public function login(Request $request){
        $this->validateLoginRequest($request);
        $user = $this->user->where('email', '=', $request->input('email'))->first();
        if(!$user)
            return response()->json(['status' => 0,'data' => "Mail id is not exist!"]); 
        if ($request->input('password') != Crypt::decrypt($user->password)) 
            return response()->json(['status' => 0,'data' => "Password is wrong!  "]);
        $results = DB::select(DB::raw('SELECT NOW() AS end_time'));
        $user->api_token = str_random(60);
        $user->expire_at = $results[0]->end_time;
        $user->save();
        $user_goal = UserGoal::where('user_id', $user->id)->count() > 0 ? 1 : 0;
        $user_profile = UserProfile::where('user_id', $user->id)->get()->toArray();
        if(count($user_profile) > 0){
         $user_profile = 1;
         $gender = $user_profile[0]['gender'];
        } else {
         $user_profile = 0;
         $gender = NULL;            
        }
        return response()->json(['status' => 1, 'message' => ucfirst($user->name)." login Sucessfully", 'userProfile' => $user_profile,
                                    'user_goal' => $user_goal, 'gender' => $gender, 'data' => $user]);
    }
    
    public function getReferralCode(Request $request){
        $user = $this->user->where('id', $request->input('user_id'))->pluck('referral_code')->toArray();    
       // echo "<pre>"; print_r($user);die;
        return response()->json(['status' => 1, 'referralcode' => $user[0]]);
    }
    
    /**
     * Method for validate user login inputs.
     *
     * @param $request
     * 
     * @return Response
     */  
    public function validateLoginRequest(Request $request){
        $rules = [
                'email' => 'required|email', 
                'password' => 'required|min:6'
            ];

        $this->validate($request, $rules);
    }//End of validateRequest  
    
    /**
     * Method for validate user inputs.
     *
     * @param $request
     * 
     * @return Response
     */  
    public function validateRequest(Request $request){
        $rules = [
                'name' => 'required',
                'email' => 'required|email|unique:users', 
                'password' => 'required|min:6'
            ];

        $this->validate($request, $rules);
    }//End of validateRequest     

    
}//End of UserController