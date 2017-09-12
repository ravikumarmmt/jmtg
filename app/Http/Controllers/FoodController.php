<?php 

namespace App\Http\Controllers;

use App\Food;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  
use Illuminate\Support\Facades\Mail;

class FoodController extends Controller
{
     /**
     * 
     * User Authentication used in construct method.
     * Authorizes user only to access store, show method 
     * 
     */
    public function __construct(Food $food) {
        $this->food = $food;
        $this->middleware('auth', ['only' => ['store', 'show', 'destroy', 'update']]);
    }//End of construct 
    
    
    public function store(Request  $request){
        $this->validateRequest($request);
        $nameExist = $this->food->where('user_id', $request->input('user_id'))->where('name', $request->input('name'))->where('isdeleted', '!=', 1)->exists();
        if($nameExist)
            return response()->json(['status' => 0, 'message' => 'Duplicate names are not allowed']);    
        $food = $this->food->create([ 
                            'user_id' => $request->input('user_id'),
                            'name' => $request->input('name'),
                            'description' => $request->input('description'),
                            'serving'=>  $request->input('serving'),
                            'units'=>  $request->input('units'),
                            'calories'=>  $request->input('calories'),
                        ]);        
        if($food)
            return response()->json(['status' => 1, 'message' => 'Successfully food information has been stored', 'data' => $food]);
        return response()->json(['status' => 1, 'message' => 'food information was not saved'], 422);
    }
    
    public function show(Request  $request){
        $this->validateShowRequest($request);
        $foodList = $this->food->where('user_id', $request->input('user_id'))->where('isdeleted', 0)->orderBy('name')->get();
        if($foodList)
            return response()->json(['status' => 1, 'data' => $foodList]);
        return response()->json(['status' => 1, 'data' => [] ]);
    }
    
    public function destroy(Request  $request){
            $this->validateDestroy($request);
            $food = $this->food->where('user_id', $request->input('user_id'))->where('id', $request->input('id'))->update(['isdeleted' => 1]);
            if(!$food)
               return response()->json(['status' => 0,'message' => "User information doesn't exist"]);
            return response()->json(['status' => 1, 'message' => "User $this->food list has been deleted"]);
    }//End of destroy     
    
    public function update(Request $request){
        $this->validateupdateRequest($request);
        $food = $this->food->find($request->input('id'));
        $dbname = (string)$food->name;
        $reqname = (string)$request->input('name');
        if(!$food)
            return response()->json(['status' => 0,'data' => "User information doesn't exist"]);
        $food->user_id = $request->input('user_id');
        $food->name = $request->input('name');
        $food->description = $request->input('description');
        $food->serving = $request->input('serving');
        $food->units = $request->input('units');
        $food->calories = $request->input('calories');
        if($dbname === $reqname){
            $food->save();
        } else  if($dbname != $reqname){
            $nameExist = $this->food->where('user_id', $request->input('user_id'))->where('name', $request->input('name'))->where('isdeleted', '!=', 1)->exists();    
            if($nameExist)
                return response()->json(['status' => 0, 'message' => 'Duplicate names are not allowed']);
            $food->save();   
        }
        return response()->json(['status' => 1, 'data' => "User $this->food list has been updated"]);
    }//End of update  

    
    public function validateShowRequest(Request $request){
        $rules = [
                'user_id' => 'required|numeric'
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
    
    public function validateupdateRequest(Request $request){
        $rules = [
                'id' => 'required|numeric',
                'user_id' => 'required|numeric',
                'name' => 'required',
                'description' => 'required',
                'serving' => 'required',
                'units' => 'required',
                'calories' => 'required',
            
            ];
        $this->validate($request, $rules);
    }//End of validateRequest 
    
    public function validateRequest(Request $request){
        $rules = [
                'user_id' => 'required|numeric',
                'name' => 'required',
                'description' => 'required',
                'serving' => 'required',
                'units' => 'required',
                'calories' => 'required',
            
            ];
        $this->validate($request, $rules);
    }//End of validateRequest 
    
    
}