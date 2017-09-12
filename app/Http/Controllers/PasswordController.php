<?php 

namespace App\Http\Controllers;

use App\User;
use App\PasswordReset;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


use Auth;


class PasswordController extends Controller
{
    /**
     * 
     * User Authentication used in construct method.
     * Authorizes user only to access sampleData method 
     * 
     */      
    public function __construct(User $user){
        $this->user = $user;
        $this->middleware('auth', ['only' => ['changePassword']]);
    }
    
    /**
     * Method for request to reset user password.
     *
     * @param $request
     * 
     * @return Response
     */      
    public function passwordReset(Request $request){
        $this->validateRequest($request);
        $user = $this->user->where('email', '=', $request->input('email'))->first();
        if(!$user)
            return response()->json(['status' => 1,'data' => "Can't find that email, sorry."]); 
        $newpassword = str_random(8);
        $password = Crypt::encrypt($newpassword);
        $userupdate = $this->user->where('email', '=', $request->input('email'))->update(['password' => $password]);
        $user = ['name' => $user->name, 'email' => $user->email, 'password' => $newpassword];
        return $this->sendMail($user);
        //return response()->json(['status' => 1,'message' => 'A new password has been sent to your e-mail address.']);  
      
   }// End of passwordReset

    public function passwordResetTemplate(){  

    }
    
    /**
     * send mail with user new credential.
     *
     * @param $request
     * 
     * @return Response
     */  
    public function sendMail($user){
        Mail::send('emails.passwordreset', ['content' => $user], function($message) use($user){
            $message->from('support@mtg.com', 'MTG');
            $message->to($user['email'], $user['name']);
            $message->subject("Hello from mail mtg");
        });
        if(count(Mail::failures()) > 0){
            return response()->json(['status' => 1,'message' => 'Failed to send password reset email, please try again.']);
        }
       return response()->json(['status' => 1,'message' => 'A new password has been sent to your e-mail address.']);
    }// End of sendMail
    
    /**
     * Method to change new password.
     *
     * @param $request
     * 
     * @return Response
     */      
    public function changePassword(Request $request){
        $this->validatechangePassword($request);
        $user = User::find($request->input('user_id'));
        if($user){
            if ($request->input('oldpassword') != Crypt::decrypt($user->password))
               return response()->json(['status' => 1,'data' => "Password doesn't match"]);
        $user->password = Crypt::encrypt($request->input('newpassowrd'));
        $user->save();    
        return response()->json(['status' => 1,'data' => "Success! Your new Password has been changed!"]);    
        }else{
            return response()->json(['status' => 0,'data' => "The user  doesn't exist"]);
        }
    }// End of changePassword
    
    /**
     * Method to validate change Password request.
     *
     * @param $request
     * 
     * @return Response
     */    
    public function validatechangePassword(Request $request){
        $rules = [
                'user_id' => 'required|numeric', 
                'oldpassword' => 'required', 
                'newpassowrd' => 'required', 
            ];

        $this->validate($request, $rules);
    }//End of validatechangePassword 
    
    /**
     * Method to validate email from request.
     *
     * @param $request
     * 
     * @return Response
     */      
    public function validateRequest(Request $request){
        $rules = [
                'email' => 'required|email', 
            ];

        $this->validate($request, $rules);
    }//End of validateRequest     
    
    
    
}// End of PasswordController
