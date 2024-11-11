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
        Schema::create('families', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->unique();
            $table->string('name');
            $table->string('job');
            $table->integer('year_of_birth');
            $table->enum('type', ['Vợ', 'Chồng', 'Con trai', 'Con gái', 'Bố đẻ', 'Mẹ đẻ', 'Bố vợ/chồng', 'Mẹ vợ/chồng']);
            $table->enum('is_living_together', ['Có', 'Không']);
            $table->text('health')->nullable();
            $table->text('situation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('families');
    }
};
