<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMunicipalHeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('municipal_heads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('fullname');
            $table->string('gender');
            $table->string('dob');
            $table->longText('picture');
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->string('password');
            $table->unsignedBigInteger('municipal_id');
            $table->foreign('municipal_id')->references('id')->on('municipals')->onDelete('cascade');
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
        Schema::dropIfExists('municipal_heads');
    }
}
