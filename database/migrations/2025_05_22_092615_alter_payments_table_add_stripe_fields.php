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
         Schema::table('payments', function (Blueprint $table) {
            $table->string('stripe_payment_id')->after('id');
            $table->unsignedBigInteger('user_id')->nullable()->after('job_id');
            $table->string('currency')->default('usd')->after('amount');

            // لو حابة تشيلي transaction_id لأنه اتغير
            $table->dropColumn('transaction_id');

            // العلاقة مع جدول users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('payments', function (Blueprint $table) {
            $table->string('stripe_payment_id')->after('id');
            $table->unsignedBigInteger('user_id')->nullable()->after('job_id');
            $table->string('currency')->default('usd')->after('amount');

            // لو حابة تشيلي transaction_id لأنه اتغير
            $table->dropColumn('transaction_id');

            // العلاقة مع جدول users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }
};
