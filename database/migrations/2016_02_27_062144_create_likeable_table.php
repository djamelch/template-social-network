<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLikeableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likeable', function(Blueprint $table) {
            $table->increments('id'); //The id of the like
            $table->integer('user_id'); //The user who like it
            //This below is slightly differant. It is a polymorphic relationship in laravel. It gives us the ability to like anything and it will be added.
            //For example if we liked a status it would have a likeable_type of /Social/Models/Statuses and a likeable_id of 1.
            //If we like an image it would use the image model Social/Models/Images and add a likeable id of 1.
            //This gives use the ability to like anthing. We are not just tied down to liking statuses.
            $table->integer('likeable_id'); //This allows like any
            $table->string('likeable_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('likeable');
    }
}
