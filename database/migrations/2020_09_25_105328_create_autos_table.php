<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('number');
            $table->string('color');
            $table->string('vin');
            $table->integer('year');
            $table->unsignedBigInteger('car_brand_id');
            $table->unsignedBigInteger('car_model_id');
            $table->timestamps();

            $table->foreign('car_brand_id')->references('id')->on('car_brands')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('car_model_id')->references('id')->on('car_models')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('autos', function (Blueprint $table) {
            $table->dropForeign('autos_car_brand_id_foreign');
            $table->dropForeign('autos_car_model_id_foreign');
        });

        Schema::dropIfExists('autos');
    }
}
