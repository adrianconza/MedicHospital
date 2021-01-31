<?php

use App\Models\MedicalExam;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_exams', function (Blueprint $table) {
            $table->id();
            $table->enum('result', array_keys(MedicalExam::RESULTS))->nullable();
            $table->foreignId('laboratory_exam_id')
                ->nullable()
                ->constrained()
                ->onUpdate('cascade');
            $table->foreignId('imaging_exam_id')
                ->nullable()
                ->constrained()
                ->onUpdate('cascade');
            $table->foreignId('medical_record_id')
                ->constrained()
                ->onUpdate('cascade');
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
        Schema::dropIfExists('medical_exams');
    }
}
