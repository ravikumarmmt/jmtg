<?php 

namespace App\Http\Controllers;

use App\Food;
use App\Recipe;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  
use Illuminate\Support\Facades\Mail;

class RecipeController extends Controller
{
    /**
     * 
     * User Authentication used in construct method.
     * Authorizes user only to access store, show method 
     * 
     */
    public function __construct() {
        $this->middleware('auth', ['only' => ['store', 'recipeWithFoodList', 'removeFoodItem', 'destroy']]);
    }//End of construct 

    public function store(Request  $request){
        $content = $request->getContent();
        $content = json_decode($content, true);
        $this->validateRequest($request);
        $nameExist = Recipe::where('user_id', $request->input('user_id'))->where('name', $content['name'])->where('isdeleted', '!=', 1)->exists();
        if($nameExist)
            return response()->json(['status' => 0, 'message' => 'Duplicate names are not allowed']);        
        $recipe = Recipe::create([ 
                            'user_id' => $content['user_id'],
                            'name' => $content['name'],
                            'food_list' => $content['food_list'],
                            'servingperunits' => $content['servingperunits'],
                        ]);        
        if($recipe)
            return response()->json(['status' => 1, 'message' => 'Successfully recipe item has been stored', 'data' => $recipe]);
        return response()->json(['status' => 1, 'message' => 'recipe item was not saved']);
    }

    public function recipeWithFoodList(Request  $request){
        $this->validateRecipeWithFoodList($request);
        $recipe = Recipe::where('user_id', $request->input('user_id'))->where('isdeleted', 0)->orderBy('name')->get()->toArray();
        if($recipe) 
            return response()->json(['status' => 1, 'data' => $recipe]);
        return response()->json(['status' => 1, 'data' => [] ]);
    }

    public function update(Request $request){
        $content = $request->getContent();
        $content = json_decode($content, true);  
        $this->validateupdateRequest($request);
        $recipe = Recipe::find($content['id']);
        $dbname = (string)$recipe->name;
        $reqname = (string)$content['name'];        
        if(!$recipe)
            return response()->json(['status' => 0,'data' => "User information doesn't exist"]);        
        $recipe->user_id = $request->input('user_id');
        $recipe->name = $content['name'];
        $recipe->food_list = $content['food_list'];
        $recipe->servingperunits = $content['servingperunits'];
        if($dbname === $reqname){
            $recipe->save();
        } else  if($dbname != $reqname){
            $nameExist = Recipe::where('user_id', $request->input('user_id'))->where('name', $content['name'])->where('isdeleted', '!=', 1)->exists();    
            if($nameExist)
                return response()->json(['status' => 0, 'message' => 'Duplicate names are not allowed']);
            $recipe->save();   
        }
        return response()->json(['status' => 1, 'data' => "User Recipe has been updated"]);
    }//End of update  

    public function destroy(Request  $request){
        $this->validateDestroy($request);
        $recipe = Recipe::where('user_id', $request->input('user_id'))->where('id', $request->input('id'))->update(['isdeleted' => 1]);
        if(!$recipe)
           return response()->json(['status' => 0,'data' => "Recipe item doesn't exist"]);
        return response()->json(['status' => 1, 'data' => "Recipe item has been deleted"]);
    } 
    
    public function validateupdateRequest(Request $request){
        $rules = [
                'user_id' => 'required|numeric',
                'id' => 'required|numeric',
                'name' => 'required',
                'food_list' => 'required',
                'servingperunits' => 'required',
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
    
    public function validateremoveFoodItem(Request $request){
        $rules = [
                'user_id' => 'required|numeric',
                'id' => 'required|numeric',
                'food_list' => 'required',            
            ];
        $this->validate($request, $rules);            
    }     
        
    
    public function validateRecipeWithFoodList(Request $request){
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
                'servingperunits' => 'required|alpha_num',
            ];
        $this->validate($request, $rules);
    }//End of validateRequest    
    
}    