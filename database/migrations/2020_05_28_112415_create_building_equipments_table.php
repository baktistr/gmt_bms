<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuildingEquipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('building_equipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id');
            $table->foreignId('building_equipment_category_id');
            $table->string('number');
            $table->string('name');
            $table->date('date_installation');
            $table->string('manufacture');
            $table->string('manufacture_model_number');
            $table->year('year_of_construction');
            $table->float('costs_center', 12);
            $table->text('location');
            $table->string('barcode_number');
            $table->string('addtional_information')->nullable();
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
        Schema::dropIfExists('building_equipments');
    }
}
