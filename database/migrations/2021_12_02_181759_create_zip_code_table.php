<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZipCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zip_code', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('zip_code', 10);
            $table->string('street')->nullable();
            $table->string('complement', 45)->nullable();
            $table->string('neighborhood', 45)->nullable();
            $table->string('city', 45)->nullable();
            $table->string('state', 5)->nullable();
            $table->string('ibge', 15)->nullable();
            $table->string('ddd', 5)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zip_code');
    }
}
