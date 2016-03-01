<?php

namespace Social\Models; //Add it to the name space Social/Models

use Illuminate\Database\Eloquent\Model; //We need the models class to make a model.

class Status extends Model{
    protected $table = 'statuses';

    protected $fillable = [
        'body'
    ];

    public function user(){
         return $this->belongsTo('Social\Models\User', 'user_id');  //This tells laravel that this status being used belongs to the user with the same id as this user_id.
    }

    //There are too differant types of statuses in the statuses table. Normal statuses and replies. We need an easy way to distinguish between them. We use a scope for that. The scope is a type of function that filters out what ever we return so this method is actually called by notReply().
    public function scopeNotReply($query){ //This scope using the query builder allows us to filter out anything we don't want.
        //This basically allows us to chain this onto our query and select any statuses that aren't replies.
        return $query->whereNull('parent_id'); //This returnst the queries that don't have a parent id. These are the normal statuses. Remember parent id's were only added for replies.
        //We also need to update our home controller. We need to add a notReply() function to the $statuses.
    }

    public function replies(){ //This creates a relationship called replies between what ever we call this for and the status model using the parent_id.
        return $this->hasMany('Social\Models\Status', 'parent_id');
    }

    public function paginatingReplies(){
        return $this->replies()->paginate(5);
    }

    public function likes(){//This gets the user who has the status, image etc that was liked.
        return $this->morphMany('Social\Models\Like', 'likeable'); //This allows laravel to pick up the model and to get the model name and set up the relationships for you. We must tell laravle the model where the polymorphic relationship is and the name of it.
    }
    //If we wanted to add likeable functionality to another model like to like a user we would add the functionalty above to the model user.php

    public function delete(){//This gets the user who has the status, image etc that was liked.
        return $this->morphMany('Social\Models\Status', 'statusId'); //This allows laravel to pick up the model and to get the model name and set up the relationships for you. We must tell laravle the model where the polymorphic relationship is and the name of it.
    }
}