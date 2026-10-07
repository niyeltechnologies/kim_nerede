<?php

use App\Models\StudentCourse;
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
        Schema::create('student_course_statements', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(StudentCourse::class);
            $table->date('course_date');
            $table->time('course_time');
            $table->integer('attended_duration');
            $table->decimal('amount_due');
            $table->integer('attended')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_course_statements');
    }
};
