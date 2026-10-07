<?php

use App\Models\StudentCourse;
use App\Models\StudentCourseSchedule;
use App\Models\StudentCourseStatement;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::call(function () {
    // your task logic
})->dailyAt('02:00');

Schedule::call(function () {
    $today = Carbon::today();

    for ($i = 0; $i < 1; $i++) {
        $currentDate = $today->copy()->addDays($i);
        $todaysCourses = StudentCourseSchedule::where('day_of_week', $currentDate->isoWeekday())->get();

        foreach ($todaysCourses as $currCourse) {
            $checkStatement = StudentCourseStatement::where('student_course_id', $currCourse->student_course_id)->where('course_time', $currCourse->start_time)->where('course_date', $currentDate->format('Y-m-d'))->get();

            if (sizeof($checkStatement)==0) {
                $studentCourseDetails = StudentCourse::where('id', $currCourse->student_course_id)->get();

                $newCourseStatementLine = [
                    'student_course_id' => $currCourse->student_course_id,
                    'course_date' => $currentDate->format('Y-m-d'),
                    'course_time' => $currCourse->start_time,
                    'amount_due' => $studentCourseDetails[0]->course_price,
                    'attended_duration' => $currCourse->duration,
                    'attended' => 0,
                ];

                $newStatment = StudentCourseStatement::create($newCourseStatementLine) ;
            }
        }
    }
})->hourly();
