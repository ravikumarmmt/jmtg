<?php

use App\User;
use App\Session;

use Laravel\Lumen\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Crypt;
use \Illuminate\Support\Facades\Cache;
use \Illuminate\Cache\ArrayStore;

class UserTest extends TestCase
{


    public function testUserCreate(){
        $api_token = str_random(60);
        $results = DB::select(DB::raw('SELECT NOW() AS end_time'));
        $user = User::create([  'name' => 'unittest',
                                'email' => 'unittest@test.com',
                                'password'=>  Crypt::encrypt('test@123'),
                                'api_token' => $api_token,
                                'expire_at' => $results[0]->end_time
                            ]);
        $this->seeInDatabase('users', ['email' => 'unittest@test.com']);
    }
    
    public function testUserLogin(){
        //$name = 'unittest';
        //$email = 'unittest@test.com';
        //$password = 'test@123';

        $name = 'Ravikumar';
        $email = 'ravik@enqos.com';
        $password = 'test@123';        
        
        $response = $this->call('POST', '/login', ['email' => 'ravik@enqos.com', 'password' => 'test@123']);
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals($data['data']['name'], $name);
        $this->assertEquals($data['data']['email'], $email);
        //$this->assertEquals($data['data']['password'],  Crypt::decrypt($password));
 
        
    } 
    
    public function testUserDelete(){
        $user_found = User::where('email', 'unittest@test.com');
        $user_found->delete();
        $this->notSeeInDatabase('users', ['email' => 'unittest@test.com']);
    }


    public function testUserNameIsRequired(){
        $response = $this->call('POST', '/register', ['name' => '', 'email' => 'mtgtest@gmail.com', 'password' => 'test123']);
        $data = json_decode($response->getContent(), true);
        $this->assertEquals($data['data']['name'], 'The name field is required.');
    }
    public function testUserEmailIsRequired(){
        $response = $this->call('POST', '/register', ['name' => 'mtgtest', 'email' => '', 'password' => 'test123']);
        $data = json_decode($response->getContent(), true);
        $this->assertEquals($data['data']['email'], 'The email field is required.');
    }
    public function testUserPasswordIsRequired(){
        $response = $this->call('POST', '/register', ['name' => 'mtgtest', 'email' => 'mtgtest@gmail.com', 'password' => '']);
        $data = json_decode($response->getContent(), true);
        $this->assertEquals($data['data']['password'], 'The password field is required.');
    }
    public function testUserPasswordMinSix(){
        $response = $this->call('POST', '/register', ['name' => 'mtgtest', 'email' => 'mtgtest@gmail.com', 'password' => '123']);
        $data = json_decode($response->getContent(), true); 
        $this->assertEquals($data['data']['password'], 'The password must be at least 6 characters.');
    }
    
    public function testUserList(){
        $this->mock = Mockery::mock('Illuminate\Database\Eloquent\Model', 'App\User');
        $this->app->instance('App\User', $this->mock);
        $this->mock->shouldReceive('all')->once()->andReturn('foo');
        $response = $this->call('GET', 'users');
        
        //echo"<pre>"; print_r($response);die;
        $this->assertEquals(200, $response->getStatusCode());
    }
   
    
}

