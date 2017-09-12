<?php 

namespace App\Http\Controllers;

use App\User;
use App\UserGoal;
use App\WeeklyUpdate;

   
use database\migrations\CreateUsersGoalTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;  
use Auth;


class DashboardController extends Controller
{
    /**
     * 
     * User Authentication used in construct method.
     * Authorizes user only to access sampleData method 
     * 
     */    
    public function __construct(User $user) {
        $this->middleware('auth', ['only' => ['store', 'getWeightLists']]);
    }//End of construct
    
    /**
     * 
     * Method to update and save user weight information in database
     * 
     * @param $request
     * 
     * @return Response
     */
    public function store(Request $request){
        $userid = $request->input('user_id');
        $data = file_get_contents('php://input');
        $data = json_decode($data);
        $content = json_decode($request->getContent());
        if($data->image == '' || $data->image == null){
            return $this->insertData($data, $userid);
        } else {
            $mime = substr($data->image, 0, strpos($data->image, ','));    
            if($mime == 'data:image/jpeg;base64' || $mime == 'data:image/png;base64'){
                if($mime == 'data:image/jpeg;base64'){
                $ext = 'jpg';    
                $dataToReplace = 'data:image/jpeg;base64,';    
                }else if( $mime == 'data:image/png;base64' ){
                $ext = 'png';
                $dataToReplace = 'data:image/png;base64,';
                }
            } else {
                return response()->json(['status' => 1, 'message' => 'File must be jpg or png.']);    
            }    
            $image_data = str_replace($dataToReplace, '', $data->image); //die;
            $image_data = base64_decode($image_data); 
            $image = imagecreatefromstring($image_data);
            $file = 'images/dashboard/'.$this->uniqueId().'.'.$ext;
            imagejpeg($image, $file, 50); // to test use  imagejpeg($image) png:  imagepng($image);
            imagedestroy($image);
            return $this->insertData($data, $userid, $file);
        }
//   $splited = explode(',', substr( $data->image , 5 ) , 2);
//   $mime=$splited[0];

//   $mime_split_without_base64=explode(';', $mime,2);
//   $mime_split=explode('/', $mime_split_without_base64[0],2);
//   echo "<pre>";print_r($mime_split); die;    
    }//End of store 
    
    public function insertData($data, $userid, $file = null){
        $weeklyupdateinsert = WeeklyUpdate::create([
                'user_id' => $userid,
                'weight' => $data->weight,
                'arm' => $data->arm,
                'waist' => $data->waist,
                'hips' => $data->hips,
                'thighs' => $data->thighs,
                'adherence' => $data->adherence,
                'menstrual_cycle' => $data->menstrual_cycle,
                'image' => $file           
        ]);
        if($weeklyupdateinsert)
            return response()->json(['status' => 1, 'message' => 'Data inserted successfully', 'data' => $weeklyupdateinsert]);
        return response()->json(['status' => 1, 'message' => 'Data was not inserted'], 422);        
    }
    
    public function uniqueId(){
        return $uniqueId = str_random(4).substr(rand(1000, 1000000000), 2, 3).str_random(5);
    }    
    /**
     * 
     * Method to show user weights based upon weekly
     * 
     * @param $request
     * 
     * @return Response
     * 
     */
    public function getWeightLists(Request $request){
        $this->validateGetWeightListsRequest($request);
        if (isset($_SERVER['HTTPS']) && !empty($_SERVER ['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
            $host = 'https' . "://" . $_SERVER['HTTP_HOST'];
        } else {
            $host = 'http' . "://" . $_SERVER['HTTP_HOST'];
        }    
        $weeklyupdate = WeeklyUpdate::where('user_id', $request->input('user_id'))->get(['id', 'user_id', 'weight', 'arm', 'waist', 'hips', 'thighs', 
                        'adherence', 'menstrual_cycle', 'image',
                        DB::raw("STR_TO_DATE(created_at, '%Y-%m-%d') as created_at"),
                        DB::raw("STR_TO_DATE(updated_at, '%Y-%m-%d') as updated_at")]);
                        
        $usergoal = UserGoal::where('user_id', $request->input('user_id'))->first(['goal_weight']);
        if(!$usergoal)
            return response()->json(['status' => 1,'message' => 'No User Goal Data']);
        $data = ['user_goal' => $usergoal->goal_weight, 'user_data' => $weeklyupdate];
        if(count($weeklyupdate)>0)
            return response()->json(['status' => 1, 'host' => $host, 'data' => $data]);
        
        $userWeightInfo = DB::table('users_profile')
                            ->join('users_goal', 'users_profile.user_id', '=', 'users_goal.user_id')
                            ->where('users_profile.user_id', $request->input('user_id'))
                            ->get(['users_profile.weight as weight', 'users_profile.weight as weight',  'users_goal.goal_weight as goal_weight']);
        $userWeightInfo = json_decode($userWeightInfo, true);
        return response()->json(['status' => 1, 'host' => $host, 'data' => $userWeightInfo]);
    }//End of getWeightLists

    /**
     * Method for validate GetWeightLists Request
     * 
     * @param $request
     * 
     * @return Response
     */  
    public function validateGetWeightListsRequest(Request $request){
        $rules = [
                'user_id' => 'required|numeric',
            ];

        $this->validate($request, $rules);
    }//End of validateGetWeightListsRequest



    
} //End Of Dashboard Controller