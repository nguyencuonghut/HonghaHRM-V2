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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('img_path');
            $table->string('private_email')->nullable()->unique();
            $table->string('company_email')->nullable()->unique();
            $table->string('phone');
            $table->string('relative_phone')->nullable();
            $table->date('date_of_birth');
            $table->string('cccd')->nullable()->unique();
            $table->date('issued_date')->nullable();
            $table->string('issued_by')->nullable();
            $table->enum('gender', ['Nam', 'Nữ']);
            $table->string('address');
            $table->foreignId('commune_id')->constrained('communes');
            $table->string('temporary_address')->nullable();
            $table->foreignId('temporary_commune_id')->nullable()->constrained('communes');
            $table->text('experience');
            $table->date('join_date');
            $table->enum('marriage_status', ['Kết hôn', 'Độc thân']);
            $table->string('bhxh')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
