<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Capsule;

class fileMigrations
{
    public function __construct()
    {
        Capsule::schema()->create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fileName');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }
}
