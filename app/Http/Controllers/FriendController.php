<?php
namespace Social\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Social\Models\User; //Remember to add teh User file so that we can use the user variable.
use Illuminate\Http\Request;

class FriendController extends Controller{
    public function getIndex(){
        $friends = Auth::user()->friends(); //Authenticate the user and get their friends.
        $requests = Auth::user()->friendRequests();

        return view('friends.index')
            ->with('friends', $friends) //Go to friends.index with the $friends variabale
            ->with('requests', $requests);
    }

    public function getAdd($username){
        $user = User::where('username', $username)->first();
        if(!$user){
            return redirect()->route('home')
                ->with('info', 'That user could not be find. Please try again');
        }

        if(Auth::user()->id === $user->id){ //If the user is trying to add themselves in the url bar by adding friends/add/{username} then redirect them back
            return redirect()->route('home');
        }

        if(Auth::user()->hasFriendRequestPending($user) || $user->hasFriendRequestPending(Auth::user())){ //check to see if either the suth user or the user has a friend request pending from the other.
            redirect()->
            route('profile.index', ['username' => $user->username])
            ->with('info', 'Friend request already pending.');
        }

        if(Auth::user()->isFriendsWith($user)){ //If the auth user tries manuelly add the user in the url bar using 'friends/add/{username}' then it will redirect to the profile pager
            return redirect()
                ->route('profile.index', ['username' => $user->username])
                ->with('info', 'You are already friends.');
        }

        Auth::user()->addFriend($user);

        return redirect()->route('profile.index', ['username' => $username])
            ->with('info', 'Friend request sent');
    }

    public function getAccept($username){
        $user = User::where('username', $username)->first();
        if(!$user){
            return redirect()
                ->route('home')
                ->with('info', 'That user could not be found');
        }

        if(!Auth::user()->hasFriendRequestReceived($user)){ //If the user is trying to accept a user that no request was sent redirect them.
            return redirect()->route('home');
        }

        Auth::user()->acceptFriendRequest($user);

        return redirect()
            ->route('profile.index', ['username' => $username]);
    }

    public function postDelete($username){
        $user = User::where('username', $username)->first();
        if(!Auth::user()->isFriendsWith($user)){ //If the auth user tries manuelly add the user in the url bar using 'friends/add/{username}' then it will redirect to the profile pager
            return redirect()->back();
        }

        Auth::user()->deleteFriend($user);

        return redirect()->back()->with('info', 'Friend deleted');
    }
}

?>