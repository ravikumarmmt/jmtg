<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->version();
});
//$app->group(['middleware' => 'cors'], function () use ($app) {
    //$app->post('/register','UserController@store');
    //$app->put('/updateprofile/{profile_id}', 'UserProfileController@update');
    //$app->put('/updategoal/{goal_id}', 'UserGoalController@update');  
//});


//Users
$app->get('/users', 'UserController@index');
$app->post('/register','UserController@store');
$app->get('/user/{user_id}','UserController@show');
$app->post('/user/{user_id}', 'UserController@update');
$app->delete('user/{user_id}', 'UserController@destroy');
$app->post('/login', 'UserController@login');
$app->post('/getreferralcode', 'UserController@getReferralCode');

$app->post('/getreferralcode', 'ReferralController@getReferralCode');
$app->post('/applyreferral', 'ReferralController@applyReferral');
$app->post('/getreferralcount', 'ReferralController@getReferralCount');

//Password
$app->post('/passwordreset','PasswordController@passwordReset');
$app->post('/changepassword','PasswordController@changePassword');


//User Profile
$app->post('/getprofile', 'UserProfileController@show');
$app->post('/saveprofile', 'UserProfileController@store');
$app->post('/updateprofile/{profile_id}', 'UserProfileController@update');

//$app->post('/test', ['middleware' => 'auth', 'uses'=>'UserController@sampleData']);
//$app->group(['middleware' => 'auth'], function () use ($app) {
//   $app->post('/test', 'UserController@sampleData');
//   $app->get('/users', 'UserController@index');
//   $app->post('/getprofile/{user_id}', function($user_id){
//       return $user_id;
//   }); 
//});

//$app->get('/user/{id}', function($id){
//    return $id;
//});
   
   
//User Goal
$app->post('/getgoal', 'UserGoalController@show');   
$app->post('/savegoal', 'UserGoalController@store');
$app->post('/updategoal/{goal_id}', 'UserGoalController@update');
$app->delete('goal/{goal_id}', 'UserGoalController@destroy');


//Food Preferences
$app->get('/foodpreferences', 'FoodprefenceController@index');
$app->post('/savefoodpreferences', 'FoodprefenceController@store');
$app->post('/updatefoodpreferences', 'FoodprefenceController@update');
$app->post('/getsubcategory', 'FoodprefenceController@getSubCategory');
$app->delete('foodpreferences/{id}', 'UserGoalController@destroy');


//MealPlan
$app->post('/mealplan', 'MealPlanController@getMealPlan');


//Dashboard
$app->post('/weight', 'DashboardController@store'); 
$app->post('/getweightlists', 'DashboardController@getWeightLists');

//Calorie Counter
$app->get('/getccmealList', 'CalorieCounterController@show');
$app->post('/saveccmealitem', 'CalorieCounterController@store');
$app->post('/updateccmealitem', 'CalorieCounterController@update');
$app->post('/deleteccmealitem', 'CalorieCounterController@destroy');
$app->post('/getccrecent', 'CalorieCounterController@search');
$app->get('/getfoodapi', 'CalorieCounterController@foodApi');


//Payment
$app->post('/savestripe', 'StripeController@store');
$app->post('/planrequest', 'PaymentController@getMtgPlanTypeRequest');
$app->post('/orderdetails', 'PaymentController@order');
$app->post('/placeorder', 'PaymentController@updatePayment');
$app->post('/freeplan', 'PaymentController@checkFreePlan');
$app->post('/plandetails', 'PaymentController@planDetails');
$app->post('/deactivate', 'PaymentController@deactivatePlan');
$app->post('/freeplanvalidity', 'PaymentController@checkFreePlanDays');

//Food
$app->post('/addfood', 'FoodController@store');
$app->get('/getfoodlist', 'FoodController@show');
$app->post('/updatefood', 'FoodController@update');
$app->post('/deletefood', 'FoodController@destroy');


//Meal
$app->post('/addmeal', 'MealController@store');
$app->get('/getmeallist', 'MealController@mealWithFoodList');
//$app->post('/deletefoodfrommeal', 'MealController@removeFoodItem');
$app->post('/updatemeal', 'MealController@update');
$app->post('/deletemeal', 'MealController@destroy');

//Recipe
$app->post('/addrecipe', 'RecipeController@store');
$app->get('/getrecipelist', 'RecipeController@recipeWithFoodList');
//$app->post('/deletefoodfromrecipe', 'RecipeController@removeFoodItem');
$app->post('/updaterecipe', 'RecipeController@update');
$app->post('/deleterecipe', 'RecipeController@destroy');

//ingredients
$app->get('/getingredients', 'IngredientsController@show');


$app->get('/dbdrop', function(){
  //Schema::dropIfExists('test'); 
});
