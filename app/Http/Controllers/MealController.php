<?php 

namespace App\Http\Controllers;

use App\Food;
use App\Meal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  
use Illuminate\Support\Facades\Mail;

class MealController extends Controller
{
    /**
     * 
     * User Authentication used in construct method.
     * Authorizes user only to access store, show method 
     * 
     */
    public function __construct() {
        $this->middleware('auth', ['only' => ['store', 'mealWithFoodList']]);
    }//End of construct 
    
    public function store(Request  $request){
        $content = $request->getContent();
        $content = json_decode($content, true);
        $this->validateRequest($request);
        $nameExist = Meal::where('user_id', $request->input('user_id'))->where('name', $content['name'])->where('isdeleted', '!=', 1)->exists();
        if($nameExist)
            return response()->json(['status' => 0, 'message' => 'Duplicate names are not allowed']);
        $meal = Meal::create([ 
                            'user_id' => $request->input('user_id'),
                            'name' => $content['name'],
                            'food_list' => $content['food_list']
                        ]);        
        if($meal)
            return response()->json(['status' => 1, 'message' => 'Successfully meal item has been stored', 'data' => $meal]);
        return response()->json(['status' => 1, 'message' => 'Meal item was not saved']);
    }
    
    public function mealWithFoodList(Request  $request){
        $this->validateMealWithFoodList($request);
        $meal = Meal::where('user_id', $request->input('user_id'))->where('isdeleted', 0)->orderBy('name')->get()->toArray();
        if($meal)
            return response()->json(['status' => 1, 'data' => $meal]);
        return response()->json(['status' => 1, 'data' => [] ]);
    }    

    public function update(Request $request){
        $content = $request->getContent();
        $content = json_decode($content, true); 
        $this->validateupdateRequest($request);
        $meal = Meal::find($content['id']);
        $dbname = (string)$meal->name;
        $reqname = (string)$content['name'];        
        if(!$meal)
            return response()->json(['status' => 0,'data' => "User information doesn't exist"]);        
        $meal->user_id = $request->input('user_id');
        $meal->name = $content['name'];
        $meal->food_list = $content['food_list'];
        if($dbname === $reqname){
            $meal->save();
        } else  if($dbname != $reqname){
            $nameExist = Meal::where('user_id', $request->input('user_id'))->where('name', $content['name'])->where('isdeleted', '!=', 1)->exists();    
            if($nameExist)
                return response()->json(['status' => 0, 'message' => 'Duplicate names are not allowed']);
            $meal->save();   
        }
        return response()->json(['status' => 1, 'data' => "User Meal has been updated"]);
    }//End of update  
    
    public function destroy(Request  $request){
        $this->validateDestroy($request);
        $meal = Meal::where('user_id', $request->input('user_id'))->where('id', $request->input('id'))->update(['isdeleted' => 1]);
        if(!$meal)
           return response()->json(['status' => 0,'data' => "Meal item doesn't exist"]);
        return response()->json(['status' => 1, 'data' => "Meal item has been deleted"]);
    }
    
    public function validateupdateRequest(Request $request){
        $rules = [
                'user_id' => 'required|numeric',
                'id' => 'required|numeric',
                'name' => 'required',
                'food_list' => 'required',            
            ];
        $this->validate($request, $rules);            
    }     
    
    public function validateDestroy(Request $request){
        $rules = [
                'user_id' => 'required|numeric',
                'id' => 'required|numeric',
            ];
        $this->validate($request, $rules);            
    }    
    public function validateMealWithFoodList(Request $request){
        $rules = [
                'user_id' => 'required|numeric'
            ];
        $this->validate($request, $rules);            
    }    
    public function validateRequest(Request $request){
        $rules = [
                'user_id' => 'required|numeric',
                'name' => 'required',
                'food_list' => 'required',
            ];
        $this->validate($request, $rules);
    }//End of validateRequest    
    
}