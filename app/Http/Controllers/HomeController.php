<?php
    namespace Social\Http\Controllers; 

    use Illuminate\Support\Facades\Auth;
    use Social\Models\Status;

    class HomeController extends Controller{ //We create create a new class that extends from our base controller(Controller.php) so we can use it makes a referance back to that controller.
        public function index(){ //We create a method that returns our home view. Later on we will have logic in here that we will return the timeline if the user is signed in.
            if(Auth::check()){ //This checks if the user is logged in and if they are returns the timeline.
                $statuses = Status::notReply()->where(function($query){ //The notReply() scope function in status.php checks to see if it has a parent_id. If it don't it will will add it as a status.
                    return $query->where('user_id', Auth::user()->id)
                        ->orWhereIn('user_id', Auth::user()->friends()->lists('id')); //This $query returns vaalues where the id is the Auth::user_id or from the Auth::users friends id's(Beccause we have many friends this is a list).
                })
                ->orderBy('created_at', 'desc')
                    ->paginate(10);


                return view('timeline.index')
                    ->with('statuses', $statuses);
            }
            return view('home');
        }
    }

?>