<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintNotificationOtherUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaint_notification_other_users', function (Blueprint $table) {
            $table->bigIncrements('complaint_notification_other_user_id_pk');
            $table->bigInteger('complaint_id_fk')->nullable();
            $table->bigInteger('user_id_fk')->nullable();
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
        Schema::dropIfExists('complaint_notification_other_users');
    }
}
