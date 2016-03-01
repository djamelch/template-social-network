<?php
namespace Social\Http\Controllers; //We need to add a namespace for every controller so we can access easily.

use Illuminate\Support\Facades\DB;
use Social\Models\User; //Remember to add teh User file so that we can use the user variable.
use Illuminate\Http\Request; //Don't forget to add this as it is our request method.

class SearchController extends Controller{ //We create create a new class that extends from our base controller(Controller.php) so we can use it makes a referance back to that controller.
    public function getResults(Request $request){ //Does the request method and returns it as $request
        $query = $request->input('query');

        if(!$query){ //If the variable query is not a query return them home
            return redirect()->route('home');
        }

        //We get the user where the name data in the database is like(similar) to the query or where it is like the username.
        $users = User::where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', "%{$query}%") //This would be very slow on a site with millions of users but for this its okay. If using for major site use elastic search.
       ->orWhere('username', 'LIKE', "%{$query}%")
       ->get();

        return view('search.results')->with('users', $users); //We send the user back with the serach result information to the page search.results
}
}

?>