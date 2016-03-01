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
    Route::get('/', [
        'uses' => '\Social\Http\Controllers\HomeController@index',
        'as' => 'home',
    ]);

    //Alert
    Route::get('/alert', function(){
        return redirect()->route('home')->with('info', 'You have signed up!'); //If the browser has a route of /alert do the callback function.
        //redirect to the route home with info of you have signed up
    });

    //Authentication
    Route::get('/signup', [ //When we have the route \signup
        'uses' => '\Social\Http\Controllers\AuthController@getSignup',
        'as' => 'auth.signup',
        'middleware' => ['guest'],
    ]);

    Route::post('/signup', [
        'uses' => '\Social\Http\Controllers\AuthController@postSignup',
        'as' => 'auth.signup',
        'middleware' => ['guest'],
    ]);

    Route::get('/signin', [
        'uses' => '\Social\Http\Controllers\AuthController@getSignin',
        'as' => 'auth.signin',
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


