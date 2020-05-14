<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDieselFuelConsumptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diesel_fuel_consumptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('building_id')->index();
            $table->string('type');
            $table->date('date')->index();
            $table->integer('amount');
            $table->integer('total_remain')->nullable();
            $table->string('description')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('diesel_fuel_consumptions');
    }
}
