<?php
namespace Social\Http\Controllers; //We need to add a namespace for every controller so we can access easily.

use Auth;
use Social\Models\User; //Remember to add teh User file so that we can use the user variable.
use Illuminate\Http\Request; //Don't forget to add this as it is our request method.

class ProfileController extends Controller{ //We create create a new class that extends from our base controller(Controller.php) so we can use it makes a referance back to that controller.
    public function getProfile($username){
        $user = User::where('username', $username)->first(); //Get the first username where the username is equal to $username from the table that we ahve difined in the class User.

        if(!$user){ //If we don't get a user abort using the error code 404.
            abort(404);
        }

        $statuses = $user->statuses()->notReply()->get();

        return view('profile.index') //We are returning to the view in profile/index with the user information form $user stored as a variable called user.
            ->with('user', $user)
            ->with('statuses', $statuses)
            ->with('authUserIsFriend', Auth::user()->isFriendsWith($user)); //This variable is either 1 or 0. Meaning is the user viewing the profile a friend or not of the Auth user.
    }

    public function getEdit(){
        return view('profile.edit');
    }

    public function postEdit(Request $request){ //Don't forget to pass in the post information
        $this->validate($request, [ //Rember the validate method takes 2 parameters the data to be validated and an array of options for validating.
            'first_name' => 'alpha|max:50', //alpha_dash: The field under validation may have alpha-numeric characters, as well as dashes and underscores.
            'last_name' => 'alpha|max:50',
            'location' => 'max:20',
            //If you want them to be able to edit more like the username add more inputs in the form and put the brains here.
        ]);

        Auth::user()->update([ //We use the Auth class and the method update from user.
            'first_name' => $request->input('first_name'), //It takes an array that we put the info from our inputs thats stored in the $request variable
            'last_name' => $request->input('last_name'),
            'location' => $request->input('location'),
        ]);

        return redirect()->route('profile.edit')->with('info', 'Your profile has been successfully updated.');
    }
}

?>