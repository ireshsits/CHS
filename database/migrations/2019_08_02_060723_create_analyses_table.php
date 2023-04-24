<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnalysesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analyses', function (Blueprint $table) {
            $table->bigIncrements('analysis_id_pk');
        	$table->string('name')->nullable();
        	$table->bigInteger('analysis_category_id_fk')->nullable();
        	$table->string('code')->nullable();
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
        Schema::dropIfExists('analyses');
    }
}
