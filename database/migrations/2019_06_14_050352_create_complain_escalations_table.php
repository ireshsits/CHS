<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplainEscalationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaint_escalations', function (Blueprint $table) {
            $table->bigIncrements('complaint_escalation_id_pk');
            $table->bigInteger('complaint_id_fk')->nullable();
            $table->bigInteger('owner_id_fk')->nullable();
        	$table->char('escalation_index',2)->nullable()->default(0);
        	$table->bigInteger('escalated_to_fk')->nullable();
        	$table->bigInteger('escalated_by_fk')->nullable();
            $table->text('remarks')->nullable();
            /**
             * INP -> Inprogress, PAS -> Pass, COM -> Completed, REJ -> Rejected
             */
            $table->enum('status', ['INP','PAS','COM','REJ'])->default('INP');
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
        Schema::dropIfExists('complaint_escalations');
    }
}
