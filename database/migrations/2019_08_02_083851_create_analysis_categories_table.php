<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnalysisCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analysis_categories', function (Blueprint $table) {
            $table->bigIncrements('analysis_category_id_pk');
        	$table->string('name')->nullable();
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
        Schema::dropIfExists('analysis_categories');
    }
}
