<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('employee_code', 30)->unique();
            $table->string('employment_type', 20)->default('employee');
            $table->string('specialization', 40)->default('general');
            $table->string('job_title')->nullable();
            $table->text('bio')->nullable();
            $table->json('skills')->nullable();
            $table->date('joined_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('internal_notes')->nullable();
            $table->timestamps();

            $table->index(['employment_type', 'specialization']);
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_profiles');
    }
};
