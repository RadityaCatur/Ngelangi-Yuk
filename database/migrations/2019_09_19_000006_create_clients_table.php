<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->nullable();

            $table->string('phone')->nullable();

            $table->string('username');

            $table->unsignedInteger('user_id');

            $table->foreign('user_id', 'client_user_fk')->references('id')->on('users')->onDelete('cascade');

            $table->integer('kuota')->nullable();

            $table->timestamps();

            $table->softDeletes();
        });
    }
}
