<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/





/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

//Don't forget. As of laravel 5.2 all routes have to be stored in the middleware web to work.
Route::group(['middleware' => ['web']], function () {
    //Home
    Route::get('/', [ //We tell it to do this action.
        'uses' => '\Social\Http\Controllers\HomeController@index', //Remember we are not in the controller gfile so we must give the namespace again. We cab cakk the method that we use here by adding @ and the method name.
        'as' => 'home',  //We give this a name. We can call it anything we want
    ]);

    //Alert
    Route::get('/alert', function(){
        return redirect()->route('home')->with('info', 'You have signed up!'); //If the browser has a route of /alert do the callback function.
        //redirect to the route home with info of you have signed up
    });

    //Authentication
    Route::get('/signup', [ //When we have the route \signup
        'uses' => '\Social\Http\Controllers\AuthController@getSignup', //Use this controller
        'as' => 'auth.signup', //We are using the auth.signup as a kind of namespace so we know what we are doing.
        'middleware' => ['guest'], //We only want to signup if the user is a guest. ie not signed in. If we look in http/Kernal.php we can see where this guest is coming from. Don't forget t update the RedirectIfAuthenticated.php file to redirect to ->route('home') instead of /
    ]);

    //This is the route for the post data. Same as above but where above gets the data and puts in the form this takes the data.
    //The reason we don't need to change the name auth.signup is because one is get and the other is post so it's differant actions
    Route::post('/signup', [
        'uses' => '\Social\Http\Controllers\AuthController@postSignup',
        'as' => 'auth.signup',
        'middleware' => ['guest'], //We only want to signup if the user is a guest. ie not signed in. If we look in http/Kernal.php we can see where this guest is coming from. Don't forget t update the RedirectIfAuthenticated.php file to redirect to ->route('home') instead of /
    ]);

    Route::get('/signin', [ //When we have the route \signup
        'uses' => '\Social\Http\Controllers\AuthController@getSignin', //Use this controller
        'as' => 'auth.signin', //We are using the auth.signup as a kind of namespace so we know what we are doing.
        'middleware' => ['guest'],
    ]);


    Route::post('/signin', [
        'uses' => '\Social\Http\Controllers\AuthController@postSignin',
        'as' => 'auth.signin',
        'middleware' => ['guest'],
    ]);

    Route::get('/signout', [
       'uses' => '\Social\Http\Controllers\AuthController@getSignout',
        'as' => 'auth.signout',
    ]);

    /*
     * Search
     */

    Route::get('/search', [
        'uses' => '\Social\Http\Controllers\SearchController@getResults',
        'as' => 'search.results',
    ]);

    /*
     * User Profile
     */
    Route::get('/user/{username}', [
        'uses' => '\Social\Http\Controllers\ProfileController@getProfile',
        'as' => 'profile.index',
    ]);

    //Remember that the user can only update their profile if they are authenticated so make sure to add in the middleware for Auth. They wouldn't be able to do anything anyway becuase they are not signed in but it's good practice to add to each file guests are not allowed use.
    Route::get('/profile/edit', [
        'uses' => '\Social\Http\Controllers\ProfileController@getEdit',
        'as' => 'profile.edit',
        'middleware' => ['auth'],
    ]);

    Route::post('/profile/edit', [
        'uses' => '\Social\Http\Controllers\ProfileController@postEdit',
        'middleware' => ['auth'],
    ]);

    /*
     * Friends
     */

    Route::get('/friends', [
        'uses' => '\Social\Http\Controllers\FriendController@getIndex',
        'as' => 'friend.index',
        'middleware' => ['auth'],
    ]);

    Route::get('/friends/add/{username}', [
        'uses' => '\Social\Http\Controllers\FriendController@getAdd',
        'as' => 'friend.add',
        'middleware' => ['auth'],
    ]);

    Route::get('/friends/accept/{username}', [
        'uses' => '\Social\Http\Controllers\FriendController@getAccept',
        'as' => 'friend.accept',
        'middleware' => ['auth'],
    ]);

    Route::post('/friends/delete/{username}', [
        'uses' => '\Social\Http\Controllers\FriendController@postDelete',
        'as' => 'friend.delete',
        'middleware' => ['auth'],
    ]);
    /*
     * Statuses
     */

    Route::post('/status', [
        'uses' => '\Social\Http\Controllers\StatusController@postStatus',
        'as' => 'status.post',
        'middleware' => ['auth'],
    ]);

    Route::post('/status/{statusId}/reply', [ //This takes the status id and passes it in to the postReply method.
        'uses' => '\Social\Http\Controllers\StatusController@postReply',
        'as' => 'status.reply',
        'middleware' => ['auth'],
    ]);

    Route::get('/status/{statusId}/like', [
        'uses' => '\Social\Http\Controllers\StatusController@getLike',
        'as' => 'status.like',
        'middleware' => ['auth'],
    ]);

    Route::get('/status/{statusId}/unlike', [
        'uses' => '\Social\Http\Controllers\StatusController@getUnlike',
        'as' => 'status.unlike',
        'middleware' => ['auth'],
    ]);

    Route::get('/status/{statusId}/delete', [
        'uses' => '\Social\Http\Controllers\StatusController@getDelete',
        'as' => 'status.delete',
        'middleware' => ['auth'],
    ]);

});


