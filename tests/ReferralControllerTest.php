<?php

use App\User;
use App\Referral;

use Laravel\Lumen\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Crypt;

use App\Http\Controllers\ReferralController;

class ReferralControllerTest extends TestCase
{
    /** @test **/
    public function makeUserLogin(){
        $response = $this->call('post', '/login', ['email' => 'ravik@enqos.com', 'password' => 'test@123']);
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $user['user_id'] = $data['data']['id'];
        $user['api_token'] = $data['data']['api_token'];        
        return $user;
    }  

    
    
    
    
    
}
