<?php
namespace Social\Http\Controllers; //We need to add a namespace for every controller so we can access easily.

use Auth;
use Illuminate\Support\Facades\DB;
use Social\Models\User; //Remember to add teh User file so that we can use the user variable.
use Social\Models\Status; //Remember to add status so we can use the functionality from the status.php file.
use Illuminate\Http\Request; //Don't forget to add this as it is our request method.

class StatusController extends Controller{ //We create create a new class that extends from our base controller(Controller.php) so we can use it makes a referance back to that controller.
    public function postStatus(Request $request){
        $this->validate($request, [
            'status' => 'required|max:1000',
        ]);

        Auth::user()->statuses()->create([ //Because we created a relationship inside the user.php file we can call the statuses method from the user() class.
            'body' => $request->input('status'),
        ]);

        return redirect()
            ->route('home')
            ->with('info', 'Status updated');

    }

    public function postReply(Request $request, $statusId)
    {
        $this->validate($request, [
            "reply-{$statusId}" => 'required|max:1000',
        ], [
            'required' => 'The reply body is required' //This gives a custom error message for required
        ]);

        $status = Status::notReply()->find($statusId); //This gets the status that is not a reply and gets its statusId sow e can reply to it.

        if (!$status) {
            return redirect()->route('home');
        }

        if (!Auth::user()->isFriendsWith($status->user) && Auth::user()->id !== $status->user->id) { //This just checks if the auth user is not friends with user and checks that if it is our own status then allow us to reply too.
            return redirect()->route('home');
        }

        $reply = Status::create([ //Creates a status
            'body' => $request->input("reply-{$statusId}"),
        ])->user()->associate(Auth::user()); //Asociates it with the Auth user. This puts the user_id set to our id.
        $status->replies()->save($reply); //Saves our reply. In laravle save function is basically an update query.
        return redirect()->back(); //Goes back to the page we were on.

    }

    public function getLike($statusId){
        $status = Status::find($statusId);

        if(!$status){ //Make sure you are liking something.
            return redirect()->route('home');
        }

        if(!Auth::user()->isFriendsWith($status->user)){ //Check that the users are friends. If their doing this their up to no good.
            return redirect()->route('home');
        }

        if(Auth::user()->hasLikedStatus($status)){
            return redirect()->back();
        }

        $like = $status->likes()->create([]); //Associates this status with the like
        Auth::user()->likes()->save($like); //Updatest the database and associates the user with the like

        return redirect()->back();
    }

    public function getUnlike($statusId){
        $status = Status::find($statusId);

        if(!$status){ //Make sure you are unliking something.
            return redirect()->route('home');
        }

        if(!Auth::user()->isFriendsWith($status->user)){ //Check that the users are friends. If their doing this their up to no good.
            return redirect()->route('home');
        }

        if(!Auth::user()->hasLikedStatus($status)){
            return redirect()->back();
        }



        Auth::user()->likes()->where('likeable_id',$status->id)->delete();

        return redirect()->back();
    }


    public function getDelete($statusId){
        $status = Status::find($statusId);

        if(!$status){ //Make sure you are liking something.
            dd('1');
            return redirect()->route('home');
        }

        DB::table('statuses')->where('id', $status->id)->delete();

        return redirect()->back();

    }
}

?>