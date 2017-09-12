<?php 

namespace App\Http\Controllers;

use App\User;
use App\Referral;



use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;  
use Auth;
use Exception;


class ReferralController extends Controller
{
    /**
     * 
     * User Authentication used in construct method.
     * Authorizes user only to access sampleData method 
     * 
     */    
    public function __construct(User $user, Referral $referral) {
        $this->user = $user;
        $this->referral = $referral;
        $this->middleware('auth', ['only' => ['getReferralCode', 'applyReferral', 'getReferralCount']]);
    }//End of construct    

    public function getReferralCode(Request $request){
        try{        
            $user = $this->user->where('id', $request->input('user_id'))->pluck('referral_code')->toArray();    
            return response()->json(['status' => 1, 'referralcode' => $user[0]]);
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'Error' => $e->getMessage()]);
        }            
    }    


    public function getReferralCount(Request $request){
        try{
            $referralCount = DB::table('referral')->select(DB::raw('count(sender) as count'))->where('sender', '=', $request->input('user_id'))->groupBy('sender')->get()->toArray();
            return response()->json(['status' => 1, 'referralcount' => $referralCount[0]->count]);
            
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'Error' => $e->getMessage()]);
        }            
    }
    
    
    public function applyReferral(Request $request){
        $this->validateApplyReferral($request);
        try{

            $user = $this->user->where('referral_code', $request->input('referral_code'))->get(['id', 'referral_code']);
            if($user[0]->id === (int)$request->input('user_id')){
                return response()->json(['status' => 1, 'Message' => 'Can\'t apply, Same User!']);    
            }
            if(count($user) < 1){
                return response()->json(['status' => 1, 'Message' => 'No user exists.!']); 
            }    
            $checkrefferral = $this->referral->where('referrer', $request->input('user_id'))->where('sender', $user[0]->id)->exists();
            if(!$checkrefferral){
                $data = [
                    'sender' => $user[0]->id,
                    'referrer' => $request->input('user_id'),
                    'credit' => 1.00
                ];
                $this->referral->create($data);
                return response()->json(['status' => 1, 'Message' => 'Data saved']);
            } else {
                return response()->json(['status' => 1, 'Message' => 'Can\'t refer more than one time.']);    
            }    
            
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'Error' =>'Line No: '.$e->getLine().' : '.$e->getMessage()]);
        }
        
    }
    
    public function validateApplyReferral(Request $request){
        $rules = [
                'referral_code' => 'required',
            ];
        $this->validate($request, $rules);        
    }
    
    
}
