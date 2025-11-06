<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('class_schedules')->cascadeOnDelete();
            $table->string('status', 10);
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('attendances');
    }
};
