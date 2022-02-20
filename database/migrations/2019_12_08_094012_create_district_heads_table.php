<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistrictHeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('district_heads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('fullname');
            $table->string('gender');
            $table->string('dob');
            $table->longText('picture');
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->string('password');
            $table->unsignedBigInteger('district_id');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');
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
        Schema::dropIfExists('district_heads');
    }
}
