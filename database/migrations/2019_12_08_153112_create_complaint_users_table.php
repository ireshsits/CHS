<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplaintUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaint_users', function (Blueprint $table) {
            $table->bigIncrements('complaint_user_id_pk');
            $table->bigInteger('complaint_id_fk')->nullable();
            $table->bigInteger('user_id_fk')->nullable();
        	/**
        	 * For the analyzing purposes.
        	 */
            $table->bigInteger('branch_department_id_fk')->nullable();
        	/**
        	 * RECPT -> Recipient, OWNER -> Owner, ESCAL -> Escalator
        	 */
        	$table->enum('user_role',['RECPT','OWNER','ESCAL'])->default('RECPT');
        	/**
        	 * User who assign the role
        	 */
        	$table->bigInteger('assigned_by_id_fk')->nullable();
        	$table->boolean('primary_owner')->default(0);
        	/**
        	 * CM is also a zonal manager level
        	 */
        	// $table->enum('system_role',['BM','RM','CM','AGM','DGM','SDGM','HO_DEPT','MKTG','CCC'])->nullable();
			$table->enum('system_role',['BM','RM','CM','AGM','DGM','SDGM','HODEPT','MKTG','CCC'])->nullable(); // CR4
        	$table->char('role_index',1)->nullable()->default(0);
        	$table->text('reject_reason')->nullable();
        	/**
        	 * ACT -> Active, 'INP' => Inprogress, ESC => Escalated, 'REP' => Replied, 'Replied-Draft', 'EREP'=> Esc-Replied, EREREP => Esc-Replied,Replied, REPTRNFR -> Replied,Transfered, EREPTRNFR-> Esc-Replied,Transfered , REJ -> Rejected
        	 */
        	$table->enum('status', ['ACT','INP','ESC','REP','REPP','EREP','EREREP','REPTRNFR','EREPTRNFR','REJ'])->default('ACT');
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
        Schema::dropIfExists('complaint_users');
    }
}
