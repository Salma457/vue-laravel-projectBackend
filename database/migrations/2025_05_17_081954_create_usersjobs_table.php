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
        Schema::create('usersjobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employer_id');
            $table->string('title');
            $table->string('work_type');
            $table->string('location');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->float('salary_from')->nullable();
            $table->float('salary_to')->nullable();
            $table->date('deadline');
            $table->text('description');
            $table->string('status')->default('active');
            $table->text('responsibilities')->nullable();
            $table->text('benefits')->nullable();
            $table->timestamps();

            $table->foreign('employer_id')->references('id')->on('employers')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usersjobs');
    }
};
