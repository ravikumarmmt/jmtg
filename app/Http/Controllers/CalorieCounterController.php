<?php 

namespace App\Http\Controllers;

use App\User;
use App\CalorieCounter;
use Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CalorieCounterController extends Controller
{

    /**
     * 
     * User Authentication used in construct method.
     * Authorizes user only to access sampleData method 
     * 
     */    
    public function __construct() {
        $this->middleware('auth', ['only' => ['show', 'store', 'update', 'destory']]);
    }//End of construct

    /*
     * 
     * method for save calorie
     *
     * @param int $id
     * 
     * @return Response
     */
    public function show(Request $request){
        //$this->validateRequest($request);
        $calorie = CalorieCounter::where('user_id', $request->input('user_id'))
                    ->whereRaw("date = '".$request->input('date')."'")
                    ->get(['id', 'mealtype', 'mealid', 'mealname', 'mealdata', 'date'])->toArray();
        $breakfast = [];$brunch = [];$lunch = [];$snack = [];$dinner = [];$dessert = [];
        foreach($calorie as $key => $val){
            if($val['mealtype'] == 'breakfast'){
                $breakfast[$key]['id'] = $val['id'];
                $breakfast[$key]['mealid'] = $val['mealid'];
                $breakfast[$key]['mealname'] = $val['mealname'];
                $breakfast[$key]['mealdata'] = $val['mealdata'];
            }
            if($val['mealtype'] == 'brunch'){
                $brunch[$key]['id'] = $val['id'];
                $brunch[$key]['mealid'] = $val['mealid'];
                $brunch[$key]['mealname'] = $val['mealname'];
                $brunch[$key]['mealdata'] = $val['mealdata'];
            }
            if($val['mealtype'] == 'lunch'){
                $lunch[$key]['id'] = $val['id'];
                $lunch[$key]['mealid'] = $val['mealid'];
                $lunch[$key]['mealname'] = $val['mealname'];
                $lunch[$key]['mealdata'] = $val['mealdata'];
            }
            if($val['mealtype'] == 'snack'){
                $snack[$key]['id'] = $val['id'];
                $snack[$key]['mealid'] = $val['mealid'];
                $snack[$key]['mealname'] = $val['mealname'];
                $snack[$key]['mealdata'] = $val['mealdata'];
            }
            if($val['mealtype'] == 'dinner'){
                $dinner[$key]['id'] = $val['id'];
                $dinner[$key]['mealid'] = $val['mealid'];
                $dinner[$key]['mealname'] = $val['mealname'];
                $dinner[$key]['mealdata'] = $val['mealdata'];
            }
            if($val['mealtype'] == 'dessert'){
                $dessert[$key]['id'] = $val['id'];
                $dessert[$key]['mealid'] = $val['mealid'];
                $dessert[$key]['mealname'] = $val['mealname'];
                $dessert[$key]['mealdata'] = $val['mealdata'];
            }                

        }
            $data = ['breakfast' => array_values($breakfast), 'brunch' => array_values($brunch), 'lunch' => array_values($lunch),
                    'snack' => array_values($snack), 'dinner' => array_values($dinner), 'dessert' => array_values($dessert)];   
         return response()->json(['status' => 1, 'data' => $data]);
    }//End of show    
    
    /*
     * 
     * method for save calorie
     * 
     * @param $request
     * 
     * @return Response
     */
    public function store(Request $request){
        $this->validateRequest($request);
        $caloriecounter = CalorieCounter::create([
                                            'user_id' => $request->input('user_id'),         
                                            'mealtype' => $request->input('mealtype'),
                                            'mealid' => $request->input('mealid'),
                                            'mealname' => $request->input('mealname'),
                                            'mealdata' => $request->input('mealdata'),
                                            'date' => date('y-m-d', strtotime($request->input('date'))),
                                        ]);
        if($caloriecounter)
            return response()->json(['status' => 1, 'message' => 'New record has been created successfully', 'mealItemId' => $caloriecounter->id]);
        return response()->json(['status' => 0, 'message' => 'Failed to insert new record '], 422);
        
    }//End of store 
    
    /**
     * Method for Update calorie count by given id.
     *
     * @param int $id
     * @param $request
     * 
     * @return Response
     */  
    public function update(Request $request){
        $caloriecounter = CalorieCounter::find($request->input('mealitemid'));
        if(!$caloriecounter)
            return response()->json(['status' => 0,'data' => "Data has been not found"]);
        $this->validateRequest($request);
       // $caloriecounter->user_id = $request->input('user_id');
        $caloriecounter->mealtype = $request->input('mealtype');
        $caloriecounter->mealid = $request->input('mealid');
        $caloriecounter->mealname = $request->input('mealname');
        $caloriecounter->mealdata = $request->input('mealdata');
        $caloriecounter->date = $request->input('date');
        $caloriecounter->save();
        return response()->json(['status' => 1, 'data' => "Data has been updated successfully"]);
    }//End of update      
    
    /**
     * Method for remove meal by id on request.
     *
     * @param int $id
     * 
     * @return Response
     */  
    public function destroy(Request $request){
        $caloriecounter = CalorieCounter::find($request->input('mealitemid'));
        if(!$caloriecounter)
            return response(user_id)->json(['status' => 0,'data' => "Data has been not found"]);
        $caloriecounter->delete();
        return response()->json(['status' => 1, 'data' => "Data has been deleted successfully"]);
    }//End of destroy    
    
    /*
     * Method for fetch data from database based on user request
     *
     * @param string $request
     * 
     * @return Response
     */ 
    public function search(Request $request){
        if($request->input('mealname') != '' || !empty($request->input('mealname'))){
            $data = CalorieCounter::where('user_id', $request->input('user_id'))->whereRaw("MATCH(mealname) AGAINST('+".$request->input('mealname')."*' IN BOOLEAN MODE )")
                    ->groupBy('mealname')->orderBy('date', 'desc');
        } else {
            $data = CalorieCounter::where('user_id', $request->input('user_id'))->groupBy('mealname')->orderBy('date', 'desc');
        }    
        $data = $data->take(100)->get()->toArray(); 
        if(!$data)
            return response()->json(['status' => 0,'data' => "No Data has been not found"]);
        return response()->json(['status' => 1, 'data' => $data]);
    }//End of search
    
    /*
     * Method for fetch data from calorie king webiste based on user request
     *
     * @param string $request
     * 
     * @return Response
     */  
    public function foodApi(Request $request){
        //$url = 'https://foodapi.calorieking.com/v1//foods';
        //  $params = "query=".$request->input('query')."&fields=servings,nutrients,volume,mass,classification,foodId,name";
        
        $url = 'https://foodapi.calorieking.com/v1//foods?query=apple&fields=servings,nutrients,volume,mass,classification,foodId,name';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
       // curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));                                                                        
        $response = curl_exec($ch);
        curl_close($ch);
        echo $response = json_decode($response, true);
        
//        if($response)
//            return $this->store($response, $user_id, $plantype);
//        return response()->json(['status' => 1, 'message' => 'OOPS! Something went wrong.'], 422);
    }//End of foodApi    
    
    /**
     * Method for validate user inputs.
     *
     * @param $request
     * 
     * @return Response
     */  
    public function validateRequest(Request $request){
        $rules = [
                'user_id' => 'required|Integer',
                'mealtype' => 'required|Alpha', 
                'mealdata' => 'required',
                'date' => 'required|date'
            ];

        $this->validate($request, $rules);
    }//End of validateRequest     
    
}// End of CalorieCounterController