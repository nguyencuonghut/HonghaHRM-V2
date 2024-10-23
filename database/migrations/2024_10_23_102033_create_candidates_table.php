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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('phone');
            $table->string('relative_phone')->nullable();
            $table->date('date_of_birth');
            $table->string('cccd')->unique()->nullable();
            $table->date('issued_date')->nullable();
            $table->string('issued_by')->nullable();
            $table->enum('gender', ['Nam', 'Nữ']);
            $table->string('address');
            $table->foreignId('commune_id')->constrained('communes')->onDelete('cascade');
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->text('experience')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
