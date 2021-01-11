<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->time('duration');
            $table->text('reason');
            $table->foreignId('patient_id')
                ->constrained()
                ->onUpdate('cascade');
            $table->foreignId('medical_speciality_id')
                ->constrained()
                ->onUpdate('cascade');
            $table->foreignId('user_id')
                ->constrained()
                ->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['start_time', 'end_time', 'patient_id', 'deleted_at']);
            $table->unique(['start_time', 'end_time', 'user_id', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}
