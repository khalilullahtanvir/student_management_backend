<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('enrollments', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('student_id');
    $table->unsignedBigInteger('course_id');
    $table->date('enroll_date')->nullable();
    $table->string('status')->default('active');
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
        Schema::dropIfExists('enrollments');
    }
};