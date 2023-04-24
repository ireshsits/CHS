<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->bigIncrements('area_id_pk');
            $table->string('name')->nullable();
            $table->string('short_name')->nullable();
            $table->string('color')->nullable();
            $table->string('reject_reason')->nullable();
            $table->bigInteger('rejected_by_fk')->nullable();
        	/**
        	* PEN -> Draft, ACT -> Active, INACT -> Inactive, REJ -> Rejected
        	*/
        	$table->enum('status', ['PEN','ACT','INACT','REJ'])->default('ACT');
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
        Schema::dropIfExists('areas');
    }
}
