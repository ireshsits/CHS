<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintCompletionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//         Schema::create('complaint_completions', function (Blueprint $table) {
//             $table->bigIncrements('complaint_completion_id_pk');            
//             $table->bigInteger('complaint_id_fk')->nullable();
//             $table->bigInteger('closed_by_id_fk')->nullable();
//             $table->text('remarks')->nullable();
//             /**
//             * INP -> Inprogress, REJ -> Rejected
//             */
//             $table->enum('status', ['INP','REJ'])->default('INP');
//             $table->timestamps();
//             $table->softDeletes();
//         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('complaint_completions');
    }
}
