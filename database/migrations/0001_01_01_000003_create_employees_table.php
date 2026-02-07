<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table): void {
            $table->id();
            $table->string('names');
            $table->string('email')->unique();
            $table->string('employee_identifier')->unique();
            $table->string('phone_number')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};

