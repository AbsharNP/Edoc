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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('patient_name');
            $table->string('address'); 
            $table->string('phone_number'); 
            $table->string('email')->nullable();
            $table->foreignId('session_id')->constrained('appsessions')->onDelete('cascade'); 
            $table->boolean('treatment_completed')->default(0); 
            $table->text('prescription')->nullable(); 
            $table->timestamps(); 
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
