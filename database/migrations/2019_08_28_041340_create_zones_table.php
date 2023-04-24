<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zones', function (Blueprint $table) {
            $table->bigIncrements('zone_id_pk');
            $table->bigInteger('manager_id_fk')->nullable();
            $table->string('name')->nullable();
            $table->integer('number')->nullable();
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
        Schema::dropIfExists('zones');
    }
}
