<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('category_id_pk');
        	$table->string('name')->nullable();
        	$table->string('color')->nullable();
        	$table->bigInteger('parent_category_id')->nullable();
        	$table->integer('category_level')->default(0)->nullable();
        	$table->string('remarks')->nullable();
        	$table->string('reject_reason')->nullable();
        	$table->bigInteger('rejected_by_fk')->nullable();
           /**
        	 * PEN -> Draft, ACT -> Active, INACT -> Inactive, REJ -> Rejected
        	*/
        	$table->enum('status', ['PEN','ACT','INACT','REJ'])->default('PEN');
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
        Schema::dropIfExists('categories');
    }
}
