<?php 

namespace App\Http\Controllers;

use App\User;
use App\Order;
use App\OrderDetails;
use App\Mtgplan;
//use App\PreferredPace;


use App\Http\Controllers\StripeController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
     /** 
     * 
     * User Authentication used in construct method.
     * Authorizes user only to access store, show method 
     * 
     */
    public function __construct() {
        $this->middleware('auth', ['only' => ['', 'order', 'store', 'update', 'destroy', 'planDetails', 'updatePayment', 'deactivatePlan', 'checkFreePlan']]);
    }//End of construct
    
    public function getMtgPlanTypeRequest(Request $request){
        $mtgplan = Mtgplan::where('name', $request->input('plan_type'))->get();
        if(!$mtgplan)
                     return response()->json(['status' => 1, 'message' => 'Choose Correct Plan type.']);        
        return response()->json(['status' => 1, 'data' => $mtgplan]);

    }    
    
    
    public function order(Request $request){
        $this->validateRequest($request);
        $orderdetails = OrderDetails::where('user_id', $request->input('user_id'))->where('plan_type', $request->input('plantype'));
        if($request->input('isfree') == 1){
            $orderdetails = $orderdetails->where('amount', 0)->first();  //one Time in History          
            if(!count($orderdetails)){    
               $data = $this->store($request->input('user_id'),  0, 0.00, 0.00, 0.00, $request->input('currency'), $request->input('plantype'), $request->input('isfree'), 7);
               return $data;                
            } else {
                return response()->json(['status' => 1, 'message' => 'Already You Utilised This Plan.']);
            }
        }else if($request->input('isfree') == 0){
            $orderdetails = $orderdetails->where('amount', '!=', 0)->where('active', 1)->first();
            if(!count($orderdetails)){    
                $mtgplan = Mtgplan::where('name', $request->input('plantype'))->first();
                if(!$mtgplan)
                     return response()->json(['status' => 1, 'message' => 'Choose Correct Plan type.']);
                if(!count(OrderDetails::where('user_id', $request->input('user_id'))->where('amount', '!=', 0)->first())){
                    $data = $this->store($request->input('user_id'),  1, $mtgplan->amount, $mtgplan->amount, 0.00, $request->input('currency'), $request->input('plantype'), $request->input('isfree'), $mtgplan->validity);
                    return $data;                                  
                }
                $editPlan = $this->editPlan($request->input('plantype'), $mtgplan->amount, $mtgplan->validity);
                $editPlan = $editPlan[0]; 
                $data = $this-> store($request->input('user_id'), $editPlan->payment, $editPlan->amount, $mtgplan->amount, $editPlan->balance_amount, $request->input('currency'), $request->input('plantype'), $request->input('isfree'), $editPlan->validity);
                return $data;
            } else {
                return response()->json(['status' => 1, 'message' => 'Your Active in this Plan.']);
            }            
        } else {
            return response()->json(['status' => 1, 'message' => 'Check Your Inputs']);             
        }
    } 
    
    public function checkFreePlanDays(Request $request){
        $orderdetails = OrderDetails::where('user_id', $request->input('user_id'))->where('amount', 0)->where('plan_type', $request->input('plantype'))
                        ->get([DB::raw("DATEDIFF(CURRENT_TIMESTAMP(), created_at) as days")])->toArray();
        if($orderdetails)
            return response()->json(['status' => 1, 'data' => $orderdetails[0]['days']]);
        return response()->json(['status' => 1, 'data' => $orderdetails[0]['days']]);
    }    
    
    public function checkFreePlan(Request $request){
        $orderdetails = OrderDetails::where('user_id', $request->input('user_id'))->where('amount', 0)->where('plan_type', $request->input('plantype'))
                        ->whereRaw('DATEDIFF(CURRENT_TIMESTAMP(), created_at) <= 7')->first();
        if($orderdetails)
            return response()->json(['status' => 1, 'data' => 1]);
        return response()->json(['status' => 1, 'data' => 0]);
    }     
    

    public function planDetails(Request $request){
        $plandetails = Db::table('order as o')
                        ->join('order_details as od', 'o.id', '=', 'od.order_id')
                        ->where('o.user_id', $request->input('user_id'))
                        ->where('od.active', 1)
                        ->get(['o.id as order_id', 
                                DB::raw("if(od.amount = 0, 1, 0) as isfree"),
                                 'od.balance_amount as balance_amount',
                                'o.amount as paidamount', 'od.amount as planamount', 'od.plan_type', 'od.active',
                                DB::raw("od.validity - DATEDIFF(CURRENT_TIMESTAMP(), od.created_at) as remaingdays")]);

        return response()->json(['status' => 1, 'data' => $plandetails]);
    } 
    
    public function deactivatePlan(Request $request){
        $OrderDetails = OrderDetails::where('user_id', $request->input('user_id'))->where('order_id', $request->input('order_id'))->where('active', 1)
                        ->update(['active' => 0]);
        if(!$OrderDetails)
            return response()->json(['status' => 1, 'message' => 'Something went wrong.']);
        return response()->json(['status' => 1, 'message' => 'Your plan has been deactivated.']);
    }     
    
    
    
    public function store($user_id, $payment, $totalamount, $planamount, $balance_amount, $currency, $plantype, $isfree, $validity){
        $active = $isfree == 0 ? ($payment == 1 ? 0 : 1) : 1;
        $order = Order::create([
                                    'user_id' => $user_id,         
                                    'amount' => $totalamount,
                                    'currency' => $currency, 
                                ]);
        
        if(!$order)
            return response()->json(['status' => 0, 'message' => 'order was not created'], 422);
        
        $order_details = OrderDetails::create([
                                    'order_id' => $order->id,
                                    'user_id' => $user_id,
                                    'plan_type' => $plantype,
                                    'amount' => $planamount,
                                    'balance_amount' => 0,
                                    'validity' => $validity,
            
                                    'active' => $active
            
                                ]);
        if(!$order_details)
            return response()->json(['status' => 1, 'message' => 'order details was not created'], 422);
        $user =  User::find($user_id);
        if($isfree == 1){
            $this->changeOrderDetailsStaus($user_id, $order->id, 0);
            //$this->sendMail($user, $order, $order_details);
            $data = ['amount' => $totalamount, 'plan_type' => $plantype, 'isfree' => 1, 'validity' => $validity];
            return response()->json(['status' => 1, 'data' => $data]);               
       } else if($isfree == 0){
            if($payment == 1){
                $data = ['payment' => $payment, 'order_id'=>$order->id, 'amountpending' => $totalamount, 'planamount' => $planamount, 'balance_amount' => $balance_amount, 'currency' => $currency, 'email' => $user->email, 'plan_type' => $plantype, 'validity' => $validity];
                return response()->json(['status' => 1, 'data' => $data]);
            } else if($payment == 0){
                //$this->sendMail($user, $order, $order_details);
                $data = ['payment' => $payment, 'order_id'=>$order->id, 'amountpending' => $totalamount, 'planamount' => $planamount,  'balance_amount' => $balance_amount, 'plan_type' => $plantype, 'validity' => $validity];
                $this->changeOrderDetailsStaus($user_id, $order->id, $data['amountpending']);
                return response()->json(['status' => 1, 'data' => $data]);
            }
        }
    }
    
    public function sendMail($user, $order, $order_details, $note=NULL){
        Mail::send('orders.orders', ['user' => $user, 'order' => $order, 'order_details' => $order_details, 'note' => $note], function($message) use($user){
            $message->from('support@mtg.com', 'MTG');
            $message->to($user->email, $user->name);
            $message->subject("OrderConfirmation From MTG");
        });
      // echo json_encode(['status' => 1,'message' => 'Order is successfull and Mail sent successfully']);
    }// End of sendMail
    
    public function updatePayment(Request $request){
        $orderid = $request->input('order_id');
        $transaction_id = $request->input('transaction_id');
        $status = $request->input('status');
        $balance_amount = $request->input('balance_amount');
        if($request->input('status') == 'success'){
            $this->updateOrder($orderid, $transaction_id, $status);
            $this->changeOrderDetailsStaus($request->input('user_id'), $orderid, $balance_amount);   
        }
        //sendMail Transaction Details;
        return response()->json(['status' => 1, 'data' => '']);
    } 
    
    public static function updateOrder($orderid, $transaction_id, $status){
        $order = Order::where('id', $orderid)->update(['transaction_id' => $transaction_id, 'status' => $status]);
        $OrderDetails = OrderDetails::where('order_id', $orderid)->update(['active' => 1]);
        if(!$order)
            return json_encode(['status' => 1, 'message' => 'Something went wrong.']);
        return json_encode(['status' => 1, 'message' => 'Order has been updated.']);
    }
    public static function changeOrderDetailsStaus($user_id, $orderid, $balance_amount){
        $orderdetails = OrderDetails::where('user_id', $user_id)->where('order_id', '!=', $orderid)->update(['active' => 0]);
         $orderdetails = OrderDetails::where('user_id', $user_id)->where('order_id', '=', $orderid)->update(['balance_amount' => $balance_amount]);
    }
 
    public static function editPlan($plantype, $amount, $validity){
        $order = Db::table('order as o')
                ->join('order_details as od', 'o.id', '=', 'od.order_id')
                ->where('od.active', 1)
                ->orderBy('o.id', 'desc')
                ->take(1)
                ->get(['o.amount as amount',
                        'od.amount as planamount',
                      DB::raw("CASE
                              WHEN od.amount > '".$amount."' THEN '-1' 
                              WHEN od.amount = '".$amount."' THEN '0' 
                              WHEN od.amount < '".$amount."' THEN '1'
                              ElSE 2
                              END 
                              as value "),
                        'od.validity as validity',
                        DB::raw("od.validity - DATEDIFF(CURRENT_TIMESTAMP(), od.created_at) as remaingdays"),
                        DB::raw("DATEDIFF(CURRENT_TIMESTAMP(), od.created_at) as totaldays"),
                        'od.balance_amount as balance_amount',
                    ]); 
        $data[] = $order = $order[0];
        $data[0]->payment = 1; //Do Payment
        if($order->value == 1){ //upgrade and add new pack
            if($order->totaldays > 0 ){  // check validity if validity more than 0 set default value
                //after reach validity 28 remaingdays get zero auomatically plan amount get full amount
                //if remaindays have any balance automically multipied and get subtract with higher plan amount 
                if($order->balance_amount > $amount){
                    $balanceamount = round($order->balance_amount/$order->validity, 2) * $order->remaingdays;
                    if($balanceamount > $amount){
                        $actualvalidity = $balanceamount * round($validity/$amount, 2);
                        $data[0]->amount = 0;
                        $data[0]->balance_amount = 0;
                        $data[0]->validity = round($actualvalidity);
                        $data[0]->payment = 1;  //No Payment bcoz here amount is high and adjust validity                     
                    } else { 
                        $data[0]->amount = $amount - $balanceamount;
                        $data[0]->balance_amount = 0;
                        $data[0]->validity = $validity;
                        $data[0]->payment = 1;                         
                    }                
                } else {
                    $data[0]->amount = $amount-(round($order->planamount/$order->validity, 2)*$order->remaingdays);
                    $data[0]->balance_amount = 0;
                    $data[0]->validity = $validity;
                    $data[0]->payment = 1;                    
                }
                
             } else {
                if($order->balance_amount > $amount){
                    $actualvalidity = $order->balance_amount * round($validity/$amount, 2);
                    $data[0]->amount = 0;
                    $data[0]->balance_amount = 0;
                    $data[0]->validity = round($actualvalidity);
                    $data[0]->payment = 1;  //No Payment bcoz here amount is high and adjust validity                      
                } else {
                    if($order->balance_amount != 0){
                        $data[0]->amount = round($amount - $order->balance_amount, 2);
                    } else {
                        $data[0]->amount = round($amount - $order->planamount, 2);
                    }
                    $data[0]->balance_amount = 0;
                    $data[0]->validity = $validity;
                    $data[0]->payment = 1;                      
                }
              
            }
        }elseif($order->value == -1){ //downgrade
            if($order->totaldays > 0 ){ // check validity if validity more than 0 
                //Balance amount in current pack
                $balanceamount = $order->totaldays == 0 ? $order->planamount : round($order->planamount/$order->validity, 2) * $order->remaingdays;
                if($balanceamount > $amount){
                    //get extra validity
                    $actualvalidity = $balanceamount * round($validity/$amount, 2); 
                    $data[0]->balance_amount = $balanceamount;
                    $data[0]->amount = 0;
                    $data[0]->validity = round($actualvalidity);
                    $data[0]->payment = 1; //No Payment bcoz here amount is high and increase validity                   
                }else if($balanceamount < $amount){
                    $data[0]->balance_amount = 0;
                    $data[0]->amount = round($amount - $balanceamount, 2);
                    $data[0]->validity = $validity;
                    $data[0]->payment = 1;                    
                }
            } else {
                $balanceamount = $order->planamount;
                $actualvalidity = $balanceamount * round($validity/$amount, 2);
                $data[0]->balance_amount = $balanceamount;
                $data[0]->amount = 0;
                $data[0]->validity = round($actualvalidity);
                $data[0]->payment = 1; //No Payment bcoz here amount is high and increase validity                
            } 
        }elseif($order->value == 0){ //same pack after validity
            $data[0]->balance_amount = 0;
            $data[0]->amount = $amount;
            $data[0]->validity = $validity;
            $data[0]->payment = 1;
        }elseif($order->value == 2){ // Add New Pack
            $data[0]->balance_amount = 0;
            $data[0]->amount = $amount;
            $data[0]->validity = $validity;
            $data[0]->payment = 1;
        }
        // echo "<pre>";print_r($data);die;
        return $data;
    } 
    
    /**
     * Method for validate user inputs.
     *
     * @param $request
     * 
     * @return Response
     */  
    public function validateRequest(Request $request){
        $rules = [
                'user_id' => 'required|numeric',
                'currency' => 'required|alpha', 
                'plantype' => 'required',
                'isfree' => 'required|numeric' 
            ];

        $this->validate($request, $rules);
    }//End of validateRequest    
}      


