<?php

use App\Food;

use Laravel\Lumen\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Crypt;

class FoodControllerTest extends TestCase
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

    /**
     * @depends makeUserLogin
     */
    public function testAddFood($user){

        $this->mock = Mockery::mock('Illuminate\Database\Eloquent\Model', 'App\Food');
        $this->app->instance('App\Food', $this->mock);
    
        $this->mock->shouldReceive('where')->once()->with('user_id', $user['user_id'])->andReturn($this->mock);
        $this->mock->shouldReceive('where')->once()->with('name', 'testfood')->andReturn($this->mock);
        $this->mock->shouldReceive('where')->once()->with('isdeleted', '!=', 1)->andReturn($this->mock);
        $this->mock->shouldReceive('exists')->once()->andReturn(false);
//        $inputs = [
//                'user_id' => $user['user_id'],
//                'name' => 'testfood',
//                'description' => 'mydescrition',
//                'serving'=>  '1 handful',
//                'units'=>  1,
//                'calories'=>  "{name:'mtgtest',email:'email@test.com'}",            
//            ];
        
        $this->mock->shouldReceive('create')->once()->andReturn("FOO");
        $response = $this->call('post', '/addfood', ['user_id' => $user['user_id'], 'api_token' => $user['api_token'], 'name' => 'testfood', 'description' => 'mydescrition', 'serving' => '1 handful', 'units' => 3,  'calories' => "{name:'mtgtest',email:'email@test.com'}" ]);  
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @depends makeUserLogin
     */
    public function testDestroyFood($user){
        $inputs = ['isdeleted' => 1];
        $this->mock = Mockery::mock('Illuminate\Database\Eloquent\Model', 'App\Food');
        $this->app->instance('App\Food', $this->mock);        
        $this->mock->shouldReceive('where')->once()->with('user_id', 1)->andReturn($this->mock);
        $this->mock->shouldReceive('where')->once()->with('id', 1)->andReturn($this->mock);
        $this->mock->shouldReceive('update')->once()->with($inputs)->andReturn(true);        
        $response = $this->call('POST', '/deletefood', ['user_id' => $user['user_id'], 'api_token' => $user['api_token'], 'id' => 1]);
        $this->assertEquals(200, $response->getStatusCode());        
    }

    /**
     * @depends makeUserLogin
     */
    public function testUpdateFood($user){
        $food = new Food;
        //$this->mocka = $this->mock = Mockery::mock('Illuminate\Database\Eloquent\Model', 'App\Food');
        $this->mock = Mockery::mock('Illuminate\Database\Eloquent\Model', 'App\Food');
        $this->app->instance('App\Food', $this->mock);
        $this->mock->shouldReceive('find')->once()->with(1)->andReturn($food);
        $food->name = 'banana';
        $food->user_id = $user['user_id'];
        $food->name = 'banana';
        $food->description = 'mydescrition';
        $food->serving = '1 handful';
        $food->units = 3;
        $food->calories = "{name:'mtgtest',email:'email@test.com'}";

        //$this->app->instance('App\Food', $this->mocka);        
        $this->mock->shouldReceive('where')->once()->with('user_id', $user['user_id'])->andReturn($this->mock);
        $this->mock->shouldReceive('where')->once()->with('name', 'testfood')->andReturn($this->mock);
        $this->mock->shouldReceive('where')->once()->with('isdeleted', '!=', 1)->andReturn($this->mock);
        $this->mock->shouldReceive('exists')->once()->andReturn(false);
        
        $food->save(); // Here not use this $this->mock->shouldReceive('save')->once()->andReturn('Foo');
        
        $response = $this->call('POST', '/updatefood', ['user_id' => $user['user_id'], 'api_token' => $user['api_token'], 'name' => 'testfood', 'description' => 'mydescrition', 'serving' => '1 handful', 'units' => 3,  'calories' => "{name:'mtgtest',email:'email@test.com'}", 'id' => 1 ]);  

        $this->assertEquals(200, $response->getStatusCode());        
    }


    
    
    
    
}
