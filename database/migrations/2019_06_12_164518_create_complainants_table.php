<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplainantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complainants', function (Blueprint $table) {
            $table->bigIncrements('complainant_id_pk');
            $table->enum ( 'title', [
                'Mr',
                'Mrs',
                'Ms',
                'Mstr',
                'Miss',
                'Prof',
                'Dr',
                'Rev'
            ] )->nullable ()->default ( 'Mr' );
            $table->string('first_name',50)->nullable();
            $table->string('last_name',75)->nullable();
            $table->string('nic',15)->nullable();
            $table->string('contact_no',15)->nullable();
            /**
             * ACT -> Active, INACT -> Inactive
             */
            $table->enum('status', ['ACT','INACT'])->default('ACT');
            
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
        Schema::dropIfExists('complainants');
    }
}
