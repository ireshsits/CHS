<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->bigIncrements('complaint_id_pk');
            $table->string('reference_number')->nullable();
            /**
             * Moved with CR2
             * Use complaint_users table to detect user related branches.
             * For analyzing preferences maintained in a separate refence to branch_department table directly.
             */
            //$table->integer('branch_department_id_fk')->nullable(); //remove in future
            $table->integer('area_id_fk')->nullable();
            $table->integer('category_id_fk')->nullable();
            //$table->integer('sub_category_id_fk')->nullable();
            $table->integer('complainant_id_fk')->nullable();
            /**
             * Moved with CR2
             * Use complaint_users table to detect user.
             */
            //             $table->integer('complaint_recepient_id_fk')->nullable();  //remove in future
            $table->integer('complaint_mode_id_fk')->nullable();
            /**
             * Moved with CR2
             * Use complaint_users table to add reject_reason for each user separately.
             */
            //             $table->string('reject_reason')->nullable();
            $table->string('account_no',20)->nullable();
            $table->text('complaint')->nullable();
            
            /**
             * CRT -> Critical, IMP -> Important, NOR -> Normal, LOW -> Low
             */
            $table->enum('priority_level', ['CRT','IMP','NOR','LOW'])->default('NOR');
            
            /**
             * CMPLA -> Complaint, CMPLI -> Compliment
             */
            $table->enum('type', ['CMPLA','CMPLI'])->default('CMPLA');
            /**
             * All -> All, MNGR -> Manager, AMNGR -> Assistant Manager, DPHD -> Department Head, ADPHD -> Assistant Department Head
             * Removed owner_role with complaint_users table in CR2.
             */
            //             $table->enum('owner_role',['ALL','MNGR','AMNGR', 'DPHD', 'ADPHD'])->default('ALL');  //remove in future
            $table->date('open_date')->nullable()->useCurrent();
            $table->date('close_date')->nullable();
            /**
             * PEN -> Draft, INP -> Inprogress, ESC -> Escalated, REP -> Replied, CLO -> Closed, REJ -> Rejected
             */
            //             $table->enum('status', ['PEN','INP','ESC','RES','REP','COM','REJ'])->default('PEN');
            $table->enum('status', ['PEN','INP','ESC','REP','COM','CLO','REJ'])->default('PEN');
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
        Schema::dropIfExists('complaints');
    }
}
