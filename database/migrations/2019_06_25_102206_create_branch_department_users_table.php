<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchDepartmentUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_department_users', function (Blueprint $table) {
            $table->bigIncrements('branch_department_user_id_pk');
            $table->bigInteger('branch_department_id_fk')->nullable();
            $table->bigInteger('user_id_fk')->nullable();
        	/**
        	 * INP -> Active, INACT -> Inactive
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
        Schema::dropIfExists('branch_department_users');
    }
}
