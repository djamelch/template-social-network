<?php

namespace Social\Models;

use Illuminate\Database\Eloquent\Model; //Don't forget we need to use the functionality from this class in the Like class.

class Like extends Model{
    protected $table = 'likeable'; //Don't forget to tell laravel the table name

    public function likeable(){
        return $this->morphTo(); //This says that I am a polymorphic model. I can be applied to anyother model like images, vidoes, statuses, etc.
    }


    public function user(){ //This gets the suer who liked the status image etc
        return $this->belongsTo('Social\Models\User', 'user_id');
    }
}