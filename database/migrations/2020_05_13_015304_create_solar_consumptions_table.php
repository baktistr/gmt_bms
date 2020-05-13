<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolarConsumptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solar_consumptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('building_id');
            $table->integer('solar_masuk');
            $table->integer('sisa_solar');
            $table->string('informasi')->nullable();
            $table->text('catatan')->nullable();
            $table->date('date');
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
        Schema::dropIfExists('solar_consumptions');
    }
}
