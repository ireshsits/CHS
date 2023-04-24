<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplainRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaint_reminders', function (Blueprint $table) {
            $table->bigIncrements('complaint_reminder_id_pk');
            $table->bigInteger('complaint_id_fk')->nullable();
            $table->bigInteger('reminded_by_fk')->nullable();
            $table->char('reminder_index',1)->nullable()->default(0);
            $table->enum('reminder_mode', ['EMAIL','SMS','CALL'])->default('EMAIL');
            $table->date('reminder_date')->nullable()->useCurrent();
            $table->text('reminder')->nullable();
            /**
             * INP -> Inprogress, COM -> Completed
             */
            $table->enum('status', ['INP','COM'])->default('INP');
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
        Schema::dropIfExists('complaint_reminders');
    }
}
