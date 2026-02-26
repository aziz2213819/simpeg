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
            $table->string('nip')->unique();
            $table->string('name');
            $table->date('birth_date')->nullable();
            $table->string('gender', 10)->nullable();
            $table->text('address')->nullable();

            // RELASI
            $table->foreignId('grade_id')
                ->constrained('grades')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('rank_id')
                ->constrained('ranks')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('position_id')
                ->constrained('positions')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            
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
