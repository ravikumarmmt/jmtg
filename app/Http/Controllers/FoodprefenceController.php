<?php 

namespace App\Http\Controllers;


use App\User;
use App\UserProfile;
use App\FoodPreferenceCategory;
use App\FoodPreferenceSubCategory;
use App\UserFoodPreference;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;  
use Auth;

class FoodprefenceController extends Controller
{
    /**
     * 
     * User Authentication used in construct method.
     * Authorizes user only to access store, show method 
     * 
     */
    public function __construct() {
       $this->middleware('auth', ['only' => ['getSubCategory', 'store']]);
    }//End of construct
       
    /**
     * 
     * Method for show food prference category
     * 
     * @return Response
     * 
     */
    public function index(){
//        $posts = Cache::remember('posts', 60, function()
//        {
          $food_preferences = FoodPreferenceCategory::with('FoodPreferenceSubCategory')->get();
//        });

        return response()->json(['status' => 1, 'data'=>$food_preferences]);    
        
    }//End of index

    /**
     * 
     * Method for save food prference details in database
     * Also same method used for update food prefrence if exist
     * 
     * @param  $request
     * 
     * @return Response
     */
    public function store(Request $request){
        $this->validateStoreRequest($request);
        $user_id = $request->input('user_id');
        $category_id = $request->input('parent_id');
        $sub_category = json_decode($request->input('sub_category'), true);
        $food_preferences = FoodPreferenceCategory::all();
        $UserFoodPreference = UserFoodPreference::where('user_id', $request->input('user_id'))->where('category_id', $request->input('parent_id'))->first();
        if($UserFoodPreference){
            if($category_id != 1){
                foreach($sub_category as $key => $value){
                    $fields = ['checked' => $value['checked']];
                    $UserFoodPreference = UserFoodPreference::where('user_id', $request->input('user_id'))
                                        ->where('category_id', $request->input('parent_id'))
                                        ->where('subcategory_id', $value['id'])
                                        ->update($fields);
                    
                }                
            } else {
 
                $UserFoodPreference = UserFoodPreference::where('user_id', $user_id)->where('category_id', 1)
                                            ->where('subcategory_id', 1)->get(['checked']);
                if(count($UserFoodPreference) > 0 && $sub_category[0]['checked'] == 1 && $sub_category[0]['id'] == 1 
                        && $sub_category[10]['checked'] != 1 && $sub_category[11]['checked'] != 1){
                   if($UserFoodPreference[0]['checked'] != $sub_category[0]['checked']){
                        $this->processVegetarian($user_id, 2);
                    }
                }

                $UserFoodPreference = UserFoodPreference::where('user_id', $user_id)->where('category_id', 1)
                                            ->where('subcategory_id', 2)->get(['checked']);                
                if(count($UserFoodPreference) > 0 && $sub_category[1]['checked'] == 1 && $sub_category[1]['id'] == 2 
                        && $sub_category[10]['checked'] != 1 && $sub_category[11]['checked'] != 1){
                   if($UserFoodPreference[0]['checked'] != $sub_category[1]['checked']){
                        $this->processPescetarian($user_id, 2);
                   }    

                } 
                
                $UserFoodPreference = UserFoodPreference::where('user_id', $user_id)->where('category_id', 1)
                                            ->where('subcategory_id', 3)->get(['checked']);                
                if(count($UserFoodPreference) > 0 && $sub_category[2]['checked'] == 1 && $sub_category[2]['id'] == 3 
                         && $sub_category[10]['checked'] != 1 && $sub_category[11]['checked'] != 1){
                   if($UserFoodPreference[0]['checked'] != $sub_category[2]['checked']){
                        $this->processVegan($user_id, 2);
                        $this->processDairy($user_id, 5, 3);
                        $this->processCondiments($user_id, 7, 3);
                   }                    
                }                
                
                $UserFoodPreference = UserFoodPreference::where('user_id', $user_id)->where('category_id', 1)
                                            ->where('subcategory_id', 9)->get(['checked']);                
                if(count($UserFoodPreference) > 0 && $sub_category[8]['checked'] == 1 && $sub_category[8]['id'] == 9){
                    if($UserFoodPreference[0]['checked'] != $sub_category[8]['checked']){
                        $this->processDairy($user_id, 5, 9);
                    }
                }                 
                
                $UserFoodPreference = UserFoodPreference::where('user_id', $user_id)->where('category_id', 1)
                                            ->where('subcategory_id', 6)->get(['checked']);             
                if(count($UserFoodPreference) > 0 && $sub_category[5]['checked'] == 1 && $sub_category[5]['id'] == 6){
                    if($UserFoodPreference[0]['checked'] != $sub_category[5]['checked']){
                        $this->processCondiments($user_id, 7, 6);
                    }
                }
                
                $UserFoodPreference = UserFoodPreference::where('user_id', $user_id)->where('category_id', 1)
                                            ->where('subcategory_id', 8)->get(['checked']);             
                if(count($UserFoodPreference) > 0 && $sub_category[7]['checked'] == 1 && $sub_category[7]['id'] == 8){
                    if($UserFoodPreference[0]['checked'] != $sub_category[7]['checked']){
                        $this->processCondiments($user_id, 7, 8);
                    }
                }
                
                $UserFoodPreference = UserFoodPreference::where('user_id', $user_id)->where('category_id', 1)
                                            ->where('subcategory_id', 7)->get(['checked']);
                if(count($UserFoodPreference) > 0 && $sub_category[6]['checked'] == 1 && $sub_category[6]['id'] == 7){
                   if($UserFoodPreference[0]['checked'] != $sub_category[6]['checked']){
                        $this->processSweetsAndSnacks($user_id, 6);
                   }
                } 
                
                $UserFoodPreference = UserFoodPreference::where('user_id', $user_id)->where('category_id', 1)
                                            ->where('subcategory_id', 11)->get(['checked']);
                $UserFoodPreference = json_decode($UserFoodPreference, true);
                if(count($UserFoodPreference) > 0 && $sub_category[10]['checked'] == 1 && $sub_category[10]['id'] == 11){
                   if($UserFoodPreference[0]['checked'] == $sub_category[10]['checked']){
                        $this->processSeaFood($user_id, 2);
                    }    
                }
                
                $UserFoodPreference = UserFoodPreference::where('user_id', $user_id)->where('category_id', 1)
                                            ->where('subcategory_id', 12)->get(['checked']);
                if(count($UserFoodPreference) > 0 && $sub_category[11]['checked'] == 1 && $sub_category[11]['id'] == 12){
                    if($UserFoodPreference[0]['checked'] != $sub_category[11]['checked']){
                        $this->processSeaFood($user_id, 2);
                    }    
                }                

                foreach($sub_category as $key => $value){
                $fields = ['checked' => $value['checked']];
                $UserFoodPreference = UserFoodPreference::where('user_id', $request->input('user_id'))
                                    ->where('category_id', $request->input('parent_id'))
                                    ->where('subcategory_id', $value['id'])
                                    ->update($fields);                      
                }         
            //----------------------    
            }
            
        } else {
            
            foreach($sub_category as $key => $value){
                $data[$key]['user_id'] = $request->input('user_id');
                $data[$key]['category_id'] = $request->input('parent_id');
                $data[$key]['subcategory_id'] = $value['id'];
                $data[$key]['checked'] = $value['checked'];
            }
            $userfoodpreference = UserFoodPreference::insert($data);
            if($userfoodpreference)
                return response()->json(['status' => 1, 'message' => 'Food prference details have been saved successfully', 'data' => $food_preferences]);
            return response()->json(['status' => 0, 'message' => 'Food prference details are not saved', 'data' => $food_preferences], 422);            
        }
    }//End of store 
    
    /**
     * Method for process vegetarian if checked in special dietry.
     * 
     * @param int $user_id
     * @param int $category_id
     * 
     * @return Response
     */     
    public function processVegetarian($user_id, $category_id){
        
        $getsubcategory = FoodPreferenceSubCategory::where('parent_id', 2)->get();
        $items = ['egg', 'tofu', 'legumes'];
        foreach($getsubcategory as $key => $val){
            if(in_array($val['name'], $items)){
                $fields = ['checked' => 1];
                $checked = 1;
            } else {
                $fields = ['checked' => 0];
                $checked = 0;
            }
            $UserFoodPreference = UserFoodPreference::where('user_id', $user_id)
                                    ->where('category_id', $category_id)
                                    ->where('subcategory_id', $val['id'])
                                    ->update($fields);
            
            if(!$UserFoodPreference)
                $userfoodpreference = UserFoodPreference::create([
                                    'user_id' => $user_id,
                                    'category_id' => $category_id,
                                    'subcategory_id' => $val['id'],                            
                                    'checked' => $checked
                                    ]);

        }
    }//End of processVegetarian 
  
    /**
     * Method for process Pescetarian if checked in special dietry.
     * 
     * @param int $user_id
     * @param int $category_id
     * 
     * @return Response
     */     
    public function processPescetarian($user_id, $category_id){
        $getsubcategory = FoodPreferenceSubCategory::where('parent_id', 2)->get();
        $items = ['egg', 'tofu', 'legumes', 'fish - white', 'fish - salmon', 'tuna'];
        foreach($getsubcategory as $key => $val){
            if(in_array($val['name'], $items)){
                $fields = ['checked' => 1];
                $checked = 1;
            } else {
                $fields = ['checked' => 0];
                $checked = 0;
            }
            $UserFoodPreference = UserFoodPreference::where('user_id', $user_id)
                                    ->where('category_id', $category_id)
                                    ->where('subcategory_id', $val['id'])
                                    ->update($fields);
            if(!$UserFoodPreference)
                $userfoodpreference = UserFoodPreference::create([
                                    'user_id' => $user_id,
                                    'category_id' => $category_id,
                                    'subcategory_id' => $val['id'],                            
                                    'checked' => $checked
                                    ]);

        }
    }//End of processPescetarian 

    /**
     * Method for process Vegan if checked in special dietry.
     * 
     * @param int $user_id
     * @param int $category_id
     * 
     * @return Response
     */         
    public function processVegan($user_id, $category_id){
        $getsubcategory = FoodPreferenceSubCategory::where('parent_id', 2)->get();
        $items = ['tofu', 'legumes'];
        foreach($getsubcategory as $key => $val){
            if(in_array($val['name'], $items)){
                $fields = ['checked' => 1];
                $checked = 1;
            } else {
                $fields = ['checked' => 0];
                $checked = 0;
            }
            $UserFoodPreference = UserFoodPreference::where('user_id', $user_id)
                                    ->where('category_id', $category_id)
                                    ->where('subcategory_id', $val['id'])
                                    ->update($fields);
            if(!$UserFoodPreference)
                $userfoodpreference = UserFoodPreference::create([
                                    'user_id' => $user_id,
                                    'category_id' => $category_id,
                                    'subcategory_id' => $val['id'],                            
                                    'checked' => $checked
                                    ]);

        }
    }//End of processVegan 

    /**
     * Method for process Dairy if soy, vegan, milk checked in special dietry.
     * 
     * @param int $user_id
     * @param int $category_id
     * 
     * @return Response
     */ 
    public function processDairy($user_id, $category_id, $value){
        $items = [];
        if(UserFoodPreference::where('user_id', $user_id)->where('category_id', 1)  
                               ->where('subcategory_id', 3)->first() && $value == 3){
            array_push($items, 'milk - almond', 'milk - soy'); 
            
        } 
        if(UserFoodPreference::where('user_id', $user_id)->where('category_id', 1)  
                           ->where('subcategory_id', 9)->first() && $value == 9){
            array_push($items, 'milk - soy');
        }
        $items = array_unique($items);
        $getsubcategory = FoodPreferenceSubCategory::where('parent_id', $category_id)->get();
        foreach($getsubcategory as $key => $val){
            if(in_array($val['name'], $items)){
                $fields = ['checked' => 0];
                $checked = 0;
            } else {
                $fields = ['checked' => 1];
                $checked = 1;
            }
            $UserFoodPreference = UserFoodPreference::where('user_id', $user_id)
                                    ->where('category_id', $category_id)
                                    ->where('subcategory_id', $val['id'])
                                    ->update($fields);
            
            if(!$UserFoodPreference)
                $userfoodpreference = UserFoodPreference::create([
                                    'user_id' => $user_id,
                                    'category_id' => $category_id,
                                    'subcategory_id' => $val['id'],                            
                                    'checked' => $checked
                                    ]);

        }
               
    }//End of processDairy  

    /**
     * Method for process Condiments if vegan or milk or tree nuts checked in special dietry.
     * 
     * @param int $user_id
     * @param int $category_id
     * 
     * @return Response
     */     
    public function processCondiments($user_id, $category_id, $value){
        $items = [];
        if(UserFoodPreference::where('user_id', $user_id)->where('category_id', 1)  
                               ->where('subcategory_id', 3)->first() && $value == 3){
            array_push($items, 'honey', 'mayonnaise', 'butter'); 
        } 
        if(UserFoodPreference::where('user_id', $user_id)->where('category_id', 1)  
                           ->where('subcategory_id', 6)->first() && $value == 6){
            array_push($items, 'butter');
        }
        if(UserFoodPreference::where('user_id', $user_id)->where('category_id', 1)  
                           ->where('subcategory_id', 8)->first() && $value == 8){
            array_push($items, 'nutella');
        }        
        $items = array_unique($items);
        $getsubcategory = FoodPreferenceSubCategory::where('parent_id', $category_id)->get();
        foreach($getsubcategory as $key => $val){
            if(in_array($val['name'], $items)){
                $fields = ['checked' => 0];
                $checked = 0;
            } else {
                $fields = ['checked' => 1];
                $checked = 1;
            }
            $UserFoodPreference = UserFoodPreference::where('user_id', $user_id)
                                    ->where('category_id', $category_id)
                                    ->where('subcategory_id', $val['id'])
                                    ->update($fields);
            
            if(!$UserFoodPreference)
                $userfoodpreference = UserFoodPreference::create([
                                    'user_id' => $user_id,
                                    'category_id' => $category_id,
                                    'subcategory_id' => $val['id'],                            
                                    'checked' => $checked
                                    ]);

        }
    }//End of processCondiments 

    /**
     * Method for process SweetsAndSnacks if pea nuts checked in special dietry.
     * 
     * @param int $user_id
     * @param int $category_id
     * 
     * @return Response
     */     
    public function processSweetsAndSnacks($user_id, $category_id){
        $items = ['nuts'];
        $getsubcategory = FoodPreferenceSubCategory::where('parent_id', $category_id)->get();
        $getsubcategory = json_decode($getsubcategory, true);
        foreach($getsubcategory as $key => $val){
            if(in_array($val['name'], $items)){
                $fields = ['checked' => 0];
                $checked = 0;
            } else {
                $fields = ['checked' => 1];
                $checked = 1;
            }
            $UserFoodPreference = UserFoodPreference::where('user_id', $user_id)
                                    ->where('category_id', $category_id)
                                    ->where('subcategory_id', $val['id'])
                                    ->update($fields);
            
            if(!$UserFoodPreference)
                $userfoodpreference = UserFoodPreference::create([
                                    'user_id' => $user_id,
                                    'category_id' => $category_id,
                                    'subcategory_id' => $val['id'],                            
                                    'checked' => $checked
                                    ]);

        }        
    }//End of processSweetsAndSnacks 

    /**
     * Method for process SeaFood if fish or shelfish checked in special dietry.
     * 
     * @param int $user_id
     * @param int $category_id
     * 
     * @return Response
     */     
    public function processSeaFood($user_id, $category_id){
        $UserFoodPreference = $this->getUserFoodPreference($user_id);
        $items = [];
        $exitems = [];
        //All comes under protein
        foreach($UserFoodPreference as $key => $val){
            if($val['name'] == 'vegetarian'){
                array_push($items, 'egg', 'tofu', 'legumes');
            }
            if($val['name'] == 'pescetarian'){
                array_push($items, 'egg', 'tofu', 'legumes', 'fish - white', 'fish - salmon', 'tuna');   
            }
            if($val['name'] == 'vegan'){
                array_push($items, 'tofu', 'legumes');   
            }
            
            if($val['name'] == 'fish'){
                array_push($exitems, 'fish - white', 'fish - salmon', 'tuna');   
            }            
            if($val['name'] == 'shellfish'){
                array_push($items, 'seafood');   
            }              
        }        
	$items = array_unique($items);
        $items = array_diff($items, $exitems);
        $exitems = array_unique($exitems);
        
        $fields = [0];
        $getsubcategory = FoodPreferenceSubCategory::where('parent_id', 2)->get();
        $getsubcategory = json_decode($getsubcategory, true);
        foreach($getsubcategory as $key => $val){
                if(in_array($val['name'], $exitems)){
                    $fields = ['checked' => 0];
                    $checked = 0;
                }
                if(in_array($val['name'], $items)){
                    $fields = ['checked' => 1];
                    $checked = 1;
                } else {
                    $fields = ['checked' => 0];
                    $checked = 0;
                }   
            $UserFoodPreference = UserFoodPreference::where('user_id', $user_id)
                                    ->where('category_id', $category_id)
                                    ->where('subcategory_id', $val['id'])
                                    ->update(['checked'=>$checked]);
        }
    }//End of processSeaFood 
    
    /**
     * 
     * Method for get specific Food preference subcategory list
     * 
     * @param  $request
     * 
     * @return Response
     */         
    public function getSubCategory(Request $request){
        $this->validateRequest($request);
        $user_id = $request->input('user_id');
        $category_id = $request->input('category_id'); 
        switch ($category_id){
            case 1://special dietary needs
                    $data = $this->getFoodPreferenceSubCategory($user_id, $category_id);
                    break;                    
            case 2://protein
                    $data = $this->showProtein($user_id, $category_id);
                    break;
            case 3://cereals
                    $data = $this->showSubCategory($user_id, $category_id);
                    break;
            case 4://fruits and vegetables
                    $data = $this->showSubCategory($user_id, $category_id);
                    break;                
            case 5://dairy
                    $data = $this->showDairy($user_id, $category_id);
                    break;
            case 6://sweets and snacks  
                    $data = $this->showSweetsAndSnacks($user_id, $category_id);
                    break;
            case 7://condiments
                    $data = $this->showCondiments($user_id, $category_id);
                    break;
            case 8://beverages
                    $data = $this->showSubCategory($user_id, $category_id);
                    break;
            case 9://supplements
                    $data = $this->showSubCategory($user_id, $category_id);
                    break;
            default:
                    $data = NULL;
        }
     // $this->toDebug($data);
       return response()->json(['status' => 1, 'data' => $data]);     
    }//End of getSubCategory 
 

    
    /**
     * 
     * Method for show datas from protein
     * 
     * @param int $user_id
     * @param int $category_id
     *  
     * @return array $out
     */        
    public function showProtein($user_id, $category_id){
    $UserFoodPreference = UserFoodPreference::where('user_id', $user_id)->where('category_id', $category_id)->get();
    $UserFoodPreference = json_decode($UserFoodPreference, true);
    if(count($UserFoodPreference) > 0){
        $FoodPreferenceSubCategory = $this->getFoodPreferenceSubCategory($user_id, $category_id);
        $out = array_values($FoodPreferenceSubCategory);
        return $out;        
    }else {
        $FoodPreferenceSubCategory = $this->getFoodPreferenceSubCategory($user_id, $category_id);
        foreach($FoodPreferenceSubCategory as $key => $val){
            $FoodPreferenceSubCategory[$key]['checked'] = 1;
        }
        $out = array_values($FoodPreferenceSubCategory);
        return $out;        
    }
    }//End of showProtein 

    /**
     * 
     * Method for show data from diary
     * 
     * @param int $user_id
     * @param int $category_id
     *  
     * @return array $out
     */     
    public function showDairy($user_id, $category_id){
        $UserFoodPreference = UserFoodPreference::where('user_id', $user_id)->where('category_id', $category_id)->first();

        if($UserFoodPreference){
            $FoodPreferenceSubCategory = $this->getFoodPreferenceSubCategory($user_id, $category_id);
            $out = array_values($FoodPreferenceSubCategory);
            return $out;                 
         } else {
            $FoodPreferenceSubCategory = $this->getFoodPreferenceSubCategory($user_id, $category_id);
            foreach($FoodPreferenceSubCategory as $key => $val){
                $FoodPreferenceSubCategory[$key]['checked'] = 1;
            }
            $out = array_values($FoodPreferenceSubCategory);
            return $out;          
        }    
    }//End of showDairy 

    /**
     * 
     * Method for show data from condiments
     * 
     * @param int $user_id
     * @param int $category_id
     *  
     * @return array $out
     */ 
    public function showCondiments($user_id, $category_id){
        $UserFoodPreference = UserFoodPreference::where('user_id', $user_id)->where('category_id', $category_id)->first();
        if($UserFoodPreference){
            $FoodPreferenceSubCategory = $this->getFoodPreferenceSubCategory($user_id, $category_id);
            $out = array_values($FoodPreferenceSubCategory);
            return $out; 
        } else {
            $FoodPreferenceSubCategory = $this->getFoodPreferenceSubCategory($user_id, $category_id);
            foreach($FoodPreferenceSubCategory as $key => $val){
                $FoodPreferenceSubCategory[$key]['checked'] = 1;
            }
            $out = array_values($FoodPreferenceSubCategory);
            return $out;          
        }              
    }//End of showCondiments 

    /**
     * 
     * Method for show data from sweat and snacks
     * 
     * @param int $user_id
     * @param int $category_id
     *  
     * @return array $out
     */ 
    public function showSweetsAndSnacks($user_id, $category_id){
        $UserFoodPreference = UserFoodPreference::where('user_id', $user_id)->where('category_id', $category_id)->get();
        $UserFoodPreference = json_decode($UserFoodPreference, true);
        if(count($UserFoodPreference) > 0){  
            $FoodPreferenceSubCategory = $this->getFoodPreferenceSubCategory($user_id, $category_id);
            foreach($FoodPreferenceSubCategory as $key => $val){
                        if($val['checked'] === 0){
                            $FoodPreferenceSubCategory[$key]['checked'] = 0;    
                        } else if($val['checked'] === 1){
                            $FoodPreferenceSubCategory[$key]['checked'] = 1;    
                        } 
            }
            $out = array_values($FoodPreferenceSubCategory);
            return $out;                 
 
        } else {
            $FoodPreferenceSubCategory = $this->getFoodPreferenceSubCategory($user_id, $category_id);
            foreach($FoodPreferenceSubCategory as $key => $val){
                $FoodPreferenceSubCategory[$key]['checked'] = 1;
            }
            $out = array_values($FoodPreferenceSubCategory);
            return $out;          
        }         
    }//End of showSweetsAndSnacks

    /**
     * 
     * Method for get user food preferences
     * 
     * @param int $user_id
     *    
     * @return array $UserFoodPreference
     */     
    public function getUserFoodPreference($user_id){
        $UserFoodPreference = DB::table('users_food_preference as upf')
                                ->join('food_preference_subcategory as fpsc', 'fpsc.id', '=', 'upf.subcategory_id')
                                ->where('upf.user_id', $user_id)
                                ->where('upf.category_id', 1)
                                ->where('upf.checked', 1)
                                ->get(['upf.subcategory_id', 'fpsc.name', 'upf.checked']);
        return $UserFoodPreference = json_decode($UserFoodPreference, true);         
    }
    public function showSubCategory($user_id, $category_id){
        $FoodPreferenceSubCategory = DB::table('food_preference_subcategory as fpsc')
                                    ->where('parent_id', $category_id)
                                    ->get(['id', 'name', DB::raw("(SELECT checked FROM `users_food_preference` WHERE subcategory_id = fpsc.id AND user_id = $user_id) as checked")]);
        
        $FoodPreferenceSubCategory = json_decode($FoodPreferenceSubCategory, true);
        
        foreach($FoodPreferenceSubCategory as $key => $val){
            if($FoodPreferenceSubCategory[$key]['checked'] === null){
                $FoodPreferenceSubCategory[$key]['checked'] = 1;
            }            
        }
        $out = array_values($FoodPreferenceSubCategory);
        return $out;  
    }//End of getUserFoodPreference  
    /**
     * 
     * Method for get user food preferences 
     * 
     *@param int $user_id
     *  
     * @return array $FoodPreferenceSubCategory
     */      
    public function getFoodPreferenceSubCategory($user_id, $category_id){
        $FoodPreferenceSubCategory = DB::table('food_preference_subcategory as fpsc')
                                    ->where('parent_id', $category_id)
                                    ->get(['id', 'name', DB::raw("(SELECT checked FROM `users_food_preference` WHERE subcategory_id = fpsc.id AND user_id = $user_id) as checked")]);
        return $FoodPreferenceSubCategory = json_decode($FoodPreferenceSubCategory, true);         
    }//End of getFoodPreferenceSubCategory       
    
    /**
     * Method for delete user  by given id.
     *
     * @param int $id
     * 
     * @return Response
     */  
    public function destroy($id){
            $UserFoodPreference = UserFoodPreference::find($id);
            if(!$UserFoodPreference)
               return response()->json(['status' => 0,'data' => "The user Food preferences details with id {$id} doesn't exist"]);
            $UserFoodPreference->delete();
            return response()->json(['status' => 1, 'data' => "The user Food preferences details with id {$id} has been deleted"]);
    }//End of destroy      

    /**
     * Method for validate food preference request.
     *
     * @param $request
     * 
     * @return Response
     */  
    public function validateRequest(Request $request){
        $rules = [
                'user_id' => 'required|numeric',
                'category_id' => 'required|numeric', 
            ];

        $this->validate($request, $rules);
    }//End of validateRequest   

    /**
     * Method for validate food preference request.
     *
     * @param $request
     * 
     * @return Response
     */  
    public function validateStoreRequest(Request $request){
        $rules = [
                'user_id' => 'required|numeric',
                'parent_id' => 'required|numeric', 
                'sub_category' => 'required'
            ];
        $this->validate($request, $rules);
    }//End of validateRequest 
    
}//End Of FoodprefenceController 

