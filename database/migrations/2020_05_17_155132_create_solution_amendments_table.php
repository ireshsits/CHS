<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolutionAmendmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solution_amendments', function (Blueprint $table) {
            $table->bigIncrements('solution_amendment_id_pk');
            $table->bigInteger('complaint_solution_id_fk')->nullable();
            $table->bigInteger('amendment_by_fk')->nullable();
            $table->text('amendment')->nullable();
            /**
             * PEN -> Draft, ACP -> VFD -> Verified, DEL -> Deleted(When doing delete(), update the status also. otherwise restore() will not trigger status update)
             */
            $table->enum('status', ['PEN','VFD','DEL'])->default('VFD');
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
        Schema::dropIfExists('solution_amendments');
    }
}
