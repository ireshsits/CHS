<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplaintModesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaint_modes', function (Blueprint $table) {
            $table->bigIncrements('complaint_mode_id_pk');
        	$table->string('name')->nullable();
        	/**
        	 * Need to add to the data saving flow. not yet added
        	 */
        	$table->string('short_name')->nullable();
        	$table->string('code',15)->nullable();
        	$table->string('color')->nullable(); 
        	/**
        	 * ACT -> Active, INACT -> Inactive
        	 */
        	$table->enum('status', ['ACT','INACT'])->default('ACT');
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
        Schema::dropIfExists('complaint_modes');
    }
}
