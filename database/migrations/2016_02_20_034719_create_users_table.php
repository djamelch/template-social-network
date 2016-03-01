<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function(Blueprint $table){ //Tells it to use the blueprint of $table thats provided by laravl.
            $table->increments('id');
            $table->string('email');
            $table->string('username');
            $table->string('password');
            $table->string('first_name')->nullable(); //This can be null
            $table->string('last_name')->nullable();
            $table->string('location')->nullable();
            $table->string('remember_token')->nullable();
            $table->timestamps(); //Adds a time stamp column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
