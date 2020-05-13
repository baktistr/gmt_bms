<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWaterConsumptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('water_consumptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id');
            $table->float('water_usage', 8, 2);
            $table->float('water_rate', 8, 2);
            $table->string('desc');
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
        Schema::dropIfExists('water_consumptions');
    }
}
