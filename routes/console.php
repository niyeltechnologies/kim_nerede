<?php

use App\Mail\DailySchedule;
use App\Models\StudentAcess;
use App\Models\StudentCourse;
use App\Models\StudentCourseSchedule;
use App\Models\StudentCourseStatement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schedule;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::call(function () {
    $userwithStudentAccess = StudentAcess::distinct()->get(['user_id']);

    $today = Carbon::today();

    foreach ($userwithStudentAccess as $currentUser) {
        $userDetails = User::where('id', $currentUser->user_id)->get();

        $userStudents = StudentAcess::with('student_detail')->where('user_id', $currentUser->user_id)->get();

        $daily_activities = [];

        foreach ($userStudents as $currStudent) {

            $studentAlCourses = StudentCourse::where('student_detail_id', $currStudent->student_detail_id)->get('id');

            $todaysCourses = StudentCourseSchedule::with('student_course')->whereIn('student_course_id', $studentAlCourses)->where('day_of_week', $today->isoWeekday())->orderBy('start_time', 'ASC')->get();

            foreach ($todaysCourses as $currCourse) {

                $currCourse = [
                    'course_time' => substr($currCourse->start_time,0,5),
                    'student_name' => $currStudent->student_detail->student_name . ' ' . $currStudent->student_detail->student_surname,
                    'course_topic' => $currCourse->student_course->course->course_topic,
                    'course_name' => $currCourse->student_course->course->course_name
                ];

                array_push($daily_activities, $currCourse);
            }
        }

        $maildata = [
            'name' => $userDetails[0]->name,
            'daily_activities' => $daily_activities,
        ];


        try {
            //      Mail::to($validated["user_email"])->queue(new UserContact($maildata));
            Mail::to($userDetails[0]->email)->send(new DailySchedule($maildata));
            Log::info('Email sent successfully', ['to' => $userDetails[0]->email]);
        } catch (TransportExceptionInterface $e) {
            Log::error('Email failed to send', [
                'to' => $userDetails[0]->email,
                'error' => $e->getMessage(),
            ]);
            return back()->withErrors(['email' => 'E-posta gönderilemedi.']);
        }
    }
})->dailyAt('02:00');

Schedule::call(function () {
    $today = Carbon::today();

    for ($i = 0; $i < 2; $i++) {
        $currentDate = $today->copy()->addDays($i);
        $todaysCourses = StudentCourseSchedule::where('day_of_week', $currentDate->isoWeekday())->get();

        foreach ($todaysCourses as $currCourse) {
            $checkStatement = StudentCourseStatement::where('student_course_id', $currCourse->student_course_id)->where('course_time', $currCourse->start_time)->where('course_date', $currentDate->format('Y-m-d'))->get();

            if (sizeof($checkStatement) == 0) {
                $studentCourseDetails = StudentCourse::where('id', $currCourse->student_course_id)->get();

                $newCourseStatementLine = [
                    'student_course_id' => $currCourse->student_course_id,
                    'course_date' => $currentDate->format('Y-m-d'),
                    'course_time' => $currCourse->start_time,
                    'amount_due' => $studentCourseDetails[0]->course_price,
                    'amount_type' => $studentCourseDetails[0]->course_price_type,
                    'attended_duration' => $currCourse->duration,
                    'attended' => 0,
                ];

                $newStatment = StudentCourseStatement::create($newCourseStatementLine);
            }
        }
    }
})->hourly();
