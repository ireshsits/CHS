<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplainSolutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaint_solutions', function (Blueprint $table) {
            $table->bigIncrements('complaint_solution_id_pk');
            $table->bigInteger('complaint_id_fk')->nullable();
            $table->bigInteger('owner_id_fk')->nullable();
            $table->bigInteger('resolved_by_fk')->nullable();
            $table->text('action_taken')->nullable();            
           /**
            * PEN -> Draft, ACP -> Accepted, NACP -> Not Accepted, VFD -> Verified, DEL -> Deleted(When doing delete(), update the status also. otherwise restore() will not trigger status update)
            */
            $table->enum('status', ['PEN','ACP','NACP', 'VFD','DEL'])->default('PEN');
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
        Schema::dropIfExists('complaint_solutions');
    }
}
