<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('usersjobs', function (Blueprint $table) {
            // حذف العمود القديم work_type
            $table->dropColumn('work_type');
        });

        Schema::table('usersjobs', function (Blueprint $table) {
            // إضافة work_type الجديد كـ enum
            $table->enum('work_type', ['full-time', 'part-time', 'remote', 'hybrid'])->after('title');

            // إضافة experience (سنوات الخبرة المطلوبة)
            $table->integer('experience')->nullable()->after('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usersjobs', function (Blueprint $table) {
            $table->dropColumn('work_type');
            $table->dropColumn('experience');

            // استرجاع العمود القديم work_type كـ string
            $table->string('work_type')->after('title');
        });
    }
};
