<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTokenUserSocialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('token_user_social', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('token_init');
            $table->integer('token_end');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('sociauth')->onDelete('cascade');
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
        Schema::drop('token_user_social');
    }
}
