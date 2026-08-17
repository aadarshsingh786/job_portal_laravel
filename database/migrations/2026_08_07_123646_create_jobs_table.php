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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employer_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->string('title');
            $table->text('description');
            $table->text('requirements');
            $table->text('benefits')->nullable();

            $table->string('location');

            $table->string('salary_min')->nullable();
            $table->string('salary_max')->nullable();

            $table->enum('job_type', [
                'full-time',
                'part-time',
                'contract',
                'temporary',
                'internship'
            ]);

            $table->string('category');

            $table->enum('experience_level', [
                'entry',
                'mid',
                'senior',
                'executive'
            ]);

            $table->date('application_deadline')->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);

            $table->integer('views')->default(0);
            $table->integer('applications_count')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
