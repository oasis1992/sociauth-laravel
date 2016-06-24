<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersociauthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sociauth', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_facebook');
            $table->string('name');
            $table->string('email');
            $table->string('avatar');
            $table->string('avatar_original');
            $table->string('gender');
            $table->rememberToken();

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
        Schema::drop('sociauth');
    }
}
