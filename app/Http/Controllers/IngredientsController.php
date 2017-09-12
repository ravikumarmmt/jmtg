<?php 

namespace App\Http\Controllers;

use App\Ingredients;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  
use Illuminate\Support\Facades\Mail;

class IngredientsController extends Controller
{
    /**
     * 
     * User Authentication used in construct method.
     * Authorizes user only to access store, show method 
     * 
     */
    public function __construct() {
        $this->middleware('auth', ['only' => ['', '']]);
    }//End of construct   
    
    
    public function show(){
        $ingredients = Ingredients::all();
        if($ingredients)
            return response()->json(['status' => 1, 'data' => $ingredients]);
        return response()->json(['status' => 1, 'data' => [] ]);
    }   
    
    
}

