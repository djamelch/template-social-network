<?php
namespace Social\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Social\Models\User; //Remember to add teh User file so that we can use the user variable.
use Illuminate\Http\Request;

class SearchController extends Controller{
    public function getResults(Request $request){
        $query = $request->input('query');

        if(!$query){
            return redirect()->route('home');
        }


        $users = User::where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', "%{$query}%")
       ->orWhere('username', 'LIKE', "%{$query}%")
       ->get();

        return view('search.results')->with('users', $users);
}
}

?>