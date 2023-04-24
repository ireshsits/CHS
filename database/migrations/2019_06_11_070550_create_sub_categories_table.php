<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//         Schema::create('sub_categories', function (Blueprint $table) {
//             $table->bigIncrements('sub_category_id_pk');
//             $table->bigInteger('category_id_fk')->nullable();
//         	$table->string('name')->nullable();
//         	$table->string('color')->nullable(); 
//         	$table->bigInteger('area_id_fk')->nullable();
//         	$table->string('reject_reason')->nullable();
//         	$table->bigInteger('rejected_by_fk')->nullable();
//         	/**
//         	 * PEN -> Draft, ACT -> Active, INACT -> Inactive, REJ -> Rejected
//         	 */
//         	$table->enum('status', ['PEN','ACT','INACT','REJ'])->default('PEN');
//         	$table->timestamps();
//         	$table->softDeletes();
//         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_categories');
    }
}
