<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstName');
            $table->string('lastName');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::create('tricks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('name');
            $table->longText('description');
            $table->string('video_url')->nullable();
            $table->timestamps();
        });
        Schema::create('user_tricks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('trick_id')->unsigned();
            $table->foreign('trick_id')->references('id')->on('tricks');
            $table->boolean('favourite');
            $table->timestamps();
        });
        Schema::create('tricks_places', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('trick_id')->unsigned();
            $table->foreign('trick_id')->references('id')->on('tricks');
            $table->string('name');
            $table->string('description')->nullable();
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
        Schema::dropIfExists('users');
        Schema::dropIfExists('tricks');
        Schema::dropIfExists('tricks_places');
        Schema::dropIfExists('user_tricks');
    }
}
