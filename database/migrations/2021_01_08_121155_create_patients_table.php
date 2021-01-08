<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('identification', 10)->unique();
            $table->string('name', 100);
            $table->string('last_name', 100);
            $table->string('email')->nullable();
            $table->string('phone', 10)->nullable();
            $table->string('address', 200);
            $table->date('birthday');
            $table->enum('gender', ['M', 'F']);
            $table->foreignId('city_id')
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
        Schema::dropIfExists('patients');
    }
}
