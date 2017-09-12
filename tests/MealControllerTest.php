<?php

use App\Food;
use App\User;
use App\Session;

use Laravel\Lumen\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Crypt;

use App\Http\Controllers\UserController;

class MealControllerTest extends TestCase
{
//    /** @test **/
//    public function GetUerLogin(){
//        $data = Session::all()->toArray();
//        $data = $data[0];
//        $user['user_id'] = $data['id'];
//        $user['api_token'] = $data['value'];
//     //   echo"<pre>"; print_r($user);die;
//    }
   
    /** @test **/
    public function makeUserLogin(){
        $response = $this->call('post', '/login', ['email' => 'ravik@enqos.com', 'password' => 'test@123']);
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $user['user_id'] = $data['data']['id'];
        $user['api_token'] = $data['data']['api_token'];        
        return $user;
    }    
    
    /**
     * @depends makeUserLogin
     */     
    public function testGetFood($user){
        $this->mock = Mockery::mock('Illuminate\Database\Eloquent\Model', 'App\Food');
        $this->app->instance('App\Food', $this->mock);
        $this->mock->shouldReceive('where')->once()->with('user_id', 1)->andReturn($this->mock);
        $this->mock->shouldReceive('where')->once()->with('isdeleted', 0)->andReturn($this->mock);
        $this->mock->shouldReceive('orderBy')->once()->with('name')->andReturn($this->mock);
        $this->mock->shouldReceive('get')->once()->andReturn("foo");
        $response = $this->call('get', '/getfoodlist', ['user_id' => $user['user_id'], 'api_token' => $user['api_token']]);
        $data = json_decode($response->getContent(), true);
        //echo"<pre>"; print_r($response);die;
        $this->assertEquals(200, $response->getStatusCode());
    }    
    
    
    
    
    
    
    
}

