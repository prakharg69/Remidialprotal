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
        Schema::create('remedial_task_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('remedial_task_id');
            $table->string('student_id');
            $table->string('file_url');
            $table->string('public_id');
            $table->string('status')->default('pending'); // pending, submitted, accepted, rejected, completed
            $table->text('feedback')->nullable();
            $table->double('score')->nullable();
            $table->text('teacher_notes')->nullable(); // private notes visible only to the teacher
            $table->dateTime('submitted_at');
            $table->dateTime('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remedial_task_submissions');
    }
};
