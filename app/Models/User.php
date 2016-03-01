<?php
//We put this file in a folder called models just to be tidy. Bigger sites wouldn't have all models in the same file.
namespace Social\Models; //Don't forget we moved this file to Models so we need to add that to the namespace.  We use this file and the class we want like 'use Social\Models\User;'

use Social\Models\Status; //We need this for the the ability to check if the user has liked a status
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model; //This is our base model which we extend below
//use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
//use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract //If we wanteded to use the CanResetPasswordContract functionality we would need to add it here seperated by a comma.
{
    use  Authenticatable; //Here we say what traits we want to use. If we wanteded to use the CanResetPasswordContract functionality we would need to add it here seperated by a comma.

    /**
     * The database table used by the model
     *
     * @var array
     */

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [ //Here we add the names of the rows in the database that we want to fill.
        'username',
        'email',
        'password',
        'first_name',
        'last_name',
        'location'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [ //These are the values that would be excluded from a json file i f we were to output this.
        'password', 'remember_token',
    ];

    public function getName(){
        if($this->first_name && $this->last_name){
            return "{$this->first_name} {$this->last_name}";
        }

        if($this->first_name){
            return $this->first_name;
        }

        return null;
    }

    public function getNameOrUsername(){
        return $this->getName() ?: $this->username; //If the function we just created getName returns a value then return that. But if not return the username.
    }

    public function getFirstNameOrUsername(){ //Say for example we were sending an email and we wanted to address themaby there first name we could create a function that would get that or their username if first_name is not present.
        return $this->first_name ?: $this->username;
    }
    //We are actually going to have 3 methods here just incase you want to chamge them or use them individually just so we have hte flexibility..

    public function getAvatarUrl(){
        return "http://www.gravatar.com/avatar/".md5($this->email)."?d=mm&s=40"; //This goes to the gravatar website and adds the avatar linked to the usr email. Also we add some options like default(d) of mysterman(mm) and a size(s) of 40.
    }

    public function statuses(){
        return $this->hasMany('Social\Models\Status', 'user_id'); ///This tells laravel that the id in the user table is the forign key user_id in the table used in the file status.
    }
    /*
     * Friends
     * We create 2 methods. First show the friends you have and the second is those who have you as their friend.
     */

    public function friendsOfMine(){
        return $this->belongsToMany('Social\Models\User', 'friends', 'user_id', 'friend_id'); //We are linking this relationship to our User file and we get the table friends and get the information that has the same id user_id and frined_id.
    }

    //The belongsToMany() function takes 4 parameters
    public function friendOf(){
        return $this->belongsToMany('Social\Models\User', 'friends', 'friend_id', 'user_id');
    }

    public function friends(){
        return $this->friendsOfMine()->wherePivot('accepted' , true)->get()->merge($this->friendOf()->wherePivot('accepted', true)->get());//We have created a realtionship based on the user_if and friend_id above in friendsOfMine() now we look to see where the accepted row in the db is true or false and return the users where the friends row is true. If we didn't do this then if one of the users wrequested another as a friend it would show them as a friend even thought they hadn't accepted them yet.
    }

    public function friendRequests(){
        return $this->friendsOfMine()->wherePivot('accepted', false)->get(); //Tis uses the friends of mine method that we created where the pivot table (friends) has an accepted value of false where someone has added the user.
    }

    ///Get pending friend requests
    public function friendRequestsPending(){
        return $this->friendOf()->wherePivot('accepted', false)->get();
    }

    //Check if user has a friend request pending from another user.
    public function hasFriendRequestPending(User $user){ //Here we pass in a user. We use this to find out does the signed in user have a friend request pending from this user.
        return (bool) $this->friendRequestsPending()->count(); //The bool property returns this function result as a booleon value.
        //This function checks if this user we are looking at has a friend request from the signed in user and returns it as a booleon value.
        //Originall was $this->friendRequestsPending()->where('id', $user->id)->count();
    }

    //Check if we have received a friend request from a user.
    public function hasFriendRequestReceived(User $user){
        return (bool) $this->friendRequests()->where('id', $user->id)->count();
        //This checks if the there is a request from the signed in user to the user we are looking at to be friends.
    }

    //Sends a friend request
    public function addFriend(User $user){
        $this->friendOf()->attach($user->id);
    }

    public function deleteFriend(User $user){
        $this->friendOf()->detach($user->id);
        $this->friendsOfMine()->detach($user->id);
    }

    //Changes the accepted value from 0 to 1
    public function acceptFriendRequest(User $user){
        $this->friendRequests()->where('id', $user->id)->first()->pivot->update([
            'accepted' => true,
        ]);
        //We pull out the users out the user from out table and we want to update the pivot table(friends) in relation to the users.
    }

    //Is friends with
    public function isFriendsWith(User $user){
        return (bool) $this->friends()->where('id', $user->id)->count();
        //This looks at the friends table using the friends method and checks if the users are friends. Remember it is either a 0 or 1 so we can return these as booleon values.
    }

    /*
     * Likes
     */

    public function likes(){
        return $this->hasMany('Social\Models\Like', 'user_id');
    }

    public function hasLikedStatus(Status $status){
        return (bool) $status->likes()->where('user_id', $this->id)->count(); //This does the same thing as below. It just checks if the status likes has a like with the from the user already.

        /*
         * This is the original method but on part 35 he said to change it.
        return (bool) $status->likes
            ->where('likeable_id', $status->id)
            ->where('likeable_type', get_class($status)) //We could hardcode that the type is status but if it changes and it's a photo we like we want to get that it;s a photo class.
            ->where('user_id', $this->id) //Get the user id
            ->count(); //Get all the instances where it satisfies the above parameters
        */
    }
}
