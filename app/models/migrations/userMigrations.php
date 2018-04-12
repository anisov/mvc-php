<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Capsule;

class userMigrations
{
    public function __construct()
    {
        Capsule::schema()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('login');
            $table->string('password');
            $table->string('name');
            $table->integer('age');
            $table->text('description');
            $table->timestamps();
        });
    }
}
