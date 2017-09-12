<?php

namespace App\Http\Controllers;

  use App\Stripe;
//use App\OrderDetails;
//use App\DietryRequirements;
//use App\PreferredPace;

use App\Http\Controllers\PaymentController;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Mail;

class StripeController extends Controller
{
     /** 
     * 
     * User Authentication used in construct method.
     * Authorizes user only to access store, show method 
     * 
     */
    public function __construct() {
        $this->middleware('auth', ['only' => ['store']]);
    }//End of construct
    
    public function store(Request $request){
        $stripe = Stripe::create([
                                    'card_no' => $request->input('cardno'),
                                    'name' => $request->input('name'),
                                    'last4' => $request->input('last4'),
                                    'type' => $request->input('type'),
                                    'amount' => $request->input('amount'),
                                    'currency' => $request->input('currency'),
                                    'result' => $request->input('result'),
                                ]);      
        
        return response()->json(['status' => 1, 'message' => 'Data is Inserted', 'data' => $stripe]);
    }
    
}

