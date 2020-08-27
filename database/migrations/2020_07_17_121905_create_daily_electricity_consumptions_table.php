<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyElectricityConsumptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_electricity_consumptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('electricity_consumption_id');
            $table->foreignId('electricity_meter_id');
            $table->bigInteger('electric_meter');
            $table->bigInteger('lwbp');
            $table->float('lwbp_rate');
            $table->bigInteger('wbp');
            $table->float('wbp_rate');
            $table->bigInteger('kvar')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('daily_electricity_consumptions');
    }
}
