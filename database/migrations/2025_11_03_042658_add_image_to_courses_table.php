<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/YYYY_MM_DD_HHMMSS_add_image_to_courses_table.php

public function up(): void
{
    Schema::table('courses', function (Blueprint $table) {
        $table->string('image')->nullable()->after('level'); // 'level' কলামের পরে যোগ হবে
    });
}

    /**
     * Reverse the migrations.
     */
   public function down(): void
{
    Schema::table('courses', function (Blueprint $table) {
        $table->dropColumn('image');
    });
}
};
