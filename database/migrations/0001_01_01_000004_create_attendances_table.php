<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->timestamp('check_in_at');
            $table->timestamp('check_out_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};

