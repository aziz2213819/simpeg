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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')
              ->constrained('employees')
              ->cascadeOnDelete();
            $table->enum('type', ['pangkat', 'gaji_berkala', 'pensiun']);
            $table->string('title');
            $table->text('message');
            $table->string('sk_file_path')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
