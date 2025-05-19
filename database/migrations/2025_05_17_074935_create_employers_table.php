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
        Schema::create('employers', function (Blueprint $table) {
            $table->id();//primary key and auto-incrementing
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('company_name');
            $table->string('location')->nullable();
            $table->string('company_website')->nullable();
            $table->string('company_logo')->nullable();
            $table->string('phone')->nullable();
            $table->text('bio')->nullable(); //newly added
            $table->text('company_description')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employers');
    }
};
