<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_departments', function (Blueprint $table) {
            $table->bigIncrements('branch_department_id_pk');
            $table->bigInteger('region_id_fk')->nullable();
            /**
             * Added as the departments not mapped to region but for a zonal level manager
             */
            $table->bigInteger('zone_id_fk')->nullable();
        	$table->integer('sol_id')->nullable();
        	$table->string('name')->nullable();
        	/**
        	 * BRN -> Branch, DEPT -> Department, SDEPT -> Marketing Department, HDEPT -> Customer call center
        	 */
        	$table->enum('type', ['BRN','DEPT','MKDEPT','CCC'])->default('BRN');
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
        Schema::dropIfExists('branch_departments');
    }
}
