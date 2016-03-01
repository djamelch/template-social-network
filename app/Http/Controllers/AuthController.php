<?php
namespace Social\Http\Controllers;
use Auth; //This allows us use the Auth class to make sure the user has only inputted the data that they are supposed to
use Social\Models\User; //We want to use the user model for registering the user.
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function getSignup(){
        return view('auth.signup');
    }

    public function postSignup(Request $request){ //access to the request/post data in the form of the variable $request
        $this->validate($request, [ //Validation
            'email' => 'required|unique:users|email|max:255',
            'username' => 'required|unique:users|alpha_dash|max:255', //alpha_dash: The field under validation may have alpha-numeric characters, as well as dashes and underscores.
            'password' => 'required|min:6',
        ]);

        User::create([
            'email' => $request->input('email'),
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password')) //The laravel function bcrypt is used to encrypt the password.
        ]);


        return redirect()
            ->route('home')
            ->with('info', 'Your account has now been created and you can now sign in.');
    }

    public function getSignin(){
        return view('auth.signin');
    }

    public function postSignin(Request $request){
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);
        if(!Auth::attempt($request->only(['email', 'password']), $request->has('remember'))){
            return redirect()->back()->with('info', 'Could not sign you in with those details.'); //This sends them back a page with the information to be displayed.
        }else{
            return redirect()->route('home')->with('info', 'You are now signed in.'); //Send the user home with 'You are now signed in' to go in the info box.
        }
    }
    public function getSignout(){
        Auth::logout();

        return redirect()->route('home')->with('info', 'You are now logged out.');
    }
}
?>