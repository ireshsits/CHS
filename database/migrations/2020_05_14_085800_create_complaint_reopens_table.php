<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintReopensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaint_reopens', function (Blueprint $table) {
            $table->bigIncrements('complaint_reopen_id_pk');
            $table->bigInteger('complaint_id_fk')->nullable();
            $table->bigInteger('reopen_by_id_fk')->nullable();
            $table->char('reopen_index',2)->nullable()->default(0);
            $table->text('reopen_reason')->nullable();            
            /**
            * CLO -> Closed Reopen , COM -> Completed Reopen
            */
            $table->enum('type', ['COM','CLO'])->nullable();
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
        Schema::dropIfExists('complaint_reopens');
    }
}
