<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplainAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaint_attachments', function (Blueprint $table) {
            $table->bigIncrements('complaint_attachment_id_pk');
            $table->bigInteger('complaint_id_fk')->nullable();
        	$table->enum('attach_type',['EML','PDF','PNG','JPEG'])->nullable();
        	$table->text('source')->nullable();            
        	/**
        	* EXST -> Exists, NEXST -> Not Exists
        	*/
        	$table->enum('status', ['EXST','NEXST'])->default('EXST');
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
        Schema::dropIfExists('complaint_attachments');
    }
}
