<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\StudentAcess;
use App\Models\StudentCourse;
use App\Models\StudentCourseSchedule;
use App\Models\StudentDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentCourseOperationsController extends Controller
{
    //
    public function getDashboard()
    {

        $userID = Auth::id();

        $userStudents = StudentAcess::where('user_id', $userID)->get('student_detail_id');

        $userStudentCourses = StudentCourse::whereIn('student_detail_id', $userStudents)->get('id');

        $userStudentCourseSchedules = StudentCourseSchedule::with('student_course')->whereIn('student_course_id', $userStudentCourses)->orderBy('start_time', 'ASC')->orderBy('day_of_week', 'ASC')->get();

        return view('dashboard', ['userStudentCourseSchedules' => $userStudentCourseSchedules]);
    }

    public function getStudentList()
    {

        $userID = Auth::id();

        $userStudents = StudentAcess::with('student_detail')->where('user_id', $userID)->get();

        return view('student_list', ['userStudents' => $userStudents]);
    }

    public function addStudentScreen()
    {
        return view('student_add');
    }

    public function addStudentSave(Request $request)
    {
        $userID = Auth::id();

        $validated = $request->validate([
            'student_name' => 'required|string|max:255',
            'student_surname' => 'required|string|max:255',
            'display_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ], [
            'student_name.required' => 'Öğrenci İsmi alanı boş bırakılamaz',
            'student_surname.required' => 'Öğrenci Soyismi alanı boş bırakılamaz',
        ]);

        $studentDetailLine = [
            'student_name' => $validated["student_name"],
            'student_surname' => $validated["student_surname"],
            'display_color' => $validated["display_color"],
            'student_owner' => $userID,
        ];

        $newStudent = StudentDetail::create($studentDetailLine);

        $newStudentId = $newStudent->id;

        $studentAccessLine = [
            'user_id' => $userID,
            'student_detail_id' => $newStudentId,
        ];

        $newStudentAccess = StudentAcess::create($studentAccessLine);


        $userStudents = StudentAcess::where('user_id', $userID)->get('student_detail_id');

        $userStudentCourses = StudentCourse::whereIn('student_detail_id', $userStudents)->get('id');

        $userStudentCourseSchedules = StudentCourseSchedule::with('student_course')->whereIn('student_course_id', $userStudentCourses)->orderBy('start_time', 'ASC')->orderBy('day_of_week', 'ASC')->get();

        return view('dashboard', ['userStudentCourseSchedules' => $userStudentCourseSchedules]);
    }

    public function getCourseList()
    {

        $userID = Auth::id();

        $userCourses = Course::where('user_id', $userID)->get();

        return view('course_list', ['userCourses' => $userCourses]);
    }

    public function addCourseScreen()
    {
        return view('course_add');
    }

    public function addCourseSave(Request $request)
    {
        $userID = Auth::id();

        $validated = $request->validate([
            'course_name' => 'required|string|max:255',
            'course_topic' => 'required|string|max:255',
        ], [
            'course_name.required' => 'Kurs (Hoca) İsmi alanı boş bırakılamaz',
            'course_topic.required' => 'Kurs Konusu alanı boş bırakılamaz',
        ]);

        $courseDetailLine = [
            'user_id' => $userID,
            'course_name' => $validated["course_name"],
            'course_topic' => $validated["course_topic"],
            'instructor_phone' => $request['instructor_phone'],
        ];

        $newCourse = Course::create($courseDetailLine);

        $userCourses = Course::where('user_id', $userID)->get();

        return view('course_list', ['userCourses' => $userCourses]);
    }

    public function getStudentCourseList()
    {

        $userID = Auth::id();

        $userStudents = StudentAcess::where('user_id', $userID)->get('student_detail_id');

        $userStudentCourses = StudentCourse::whereIn('student_detail_id', $userStudents)->get('id');

        $userStudentCourseSchedules = StudentCourseSchedule::with('student_course')->whereIn('student_course_id', $userStudentCourses)->orderBy('student_course_id', 'ASC')->orderBy('day_of_week', 'ASC')->orderBy('start_time', 'ASC')->get();

        return view('student_course_list', ['userStudentCourseSchedules' => $userStudentCourseSchedules]);
    }

    public function addStudentCourseScreen()
    {
        $userID = Auth::id();

        $userCourses = Course::where('user_id', $userID)->get();
        $userStudents = StudentAcess::with('student_detail')->where('user_id', $userID)->get();

        return view('student_course_add', ['userCourses' => $userCourses, 'userStudents' => $userStudents,]);
    }

    public function addStudentCourseSave(Request $request)
    {
        $userID = Auth::id();

        $validated = $request->validate([
            'student_detail_id' => 'required|integer',
            'course_id' => 'required|integer',
            'course_price' => 'required|numeric',
            'course_price_type' => 'required|integer',
            'day_of_week' => 'required|integer|min:1|max:7',
            'start_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:1',
        ], [
            'student_detail_id.required' => 'Öğrenci seçim alanı boş bırakılamaz',
            'course_id.required' => 'Kurs seçim alanı boş bırakılamaz',
            'course_price.required' => 'Kurs Ücreti alanı boş bırakılamaz',
            'course_price_type.required' => 'Ücret Türü alanı boş bırakılamaz',
            'day_of_week.required' => 'Kurs Günü seçim alanı boş bırakılamaz',
            'start_time.required' => 'Kurs Ücreti alanı boş bırakılamaz',
            'duration.required' => 'Kurs Süresi alanı boş bırakılamaz',
        ]);

        $studentCourseLine = [
            'student_detail_id' => $validated["student_detail_id"],
            'course_id' => $validated["course_id"],
            'course_price' => $validated["course_price"],
            'course_price_type' => $validated["course_price_type"],
        ];

        $newStudentCourse = StudentCourse::create($studentCourseLine);

        $newStudentCourseId = $newStudentCourse->id;

        $studentCourseScheduleLine = [
            'student_course_id' => $newStudentCourseId,
            'day_of_week' => $validated["day_of_week"],
            'start_time' => $validated["start_time"],
            'duration' => $validated["duration"],
        ];

        $newStudentCourseSchedule = StudentCourseSchedule::create($studentCourseScheduleLine);

        $userID = Auth::id();

        $userStudents = StudentAcess::where('user_id', $userID)->get('student_detail_id');

        $userStudentCourses = StudentCourse::whereIn('student_detail_id', $userStudents)->get('id');

        $userStudentCourseSchedules = StudentCourseSchedule::with('student_course')->whereIn('student_course_id', $userStudentCourses)->orderBy('student_course_id', 'ASC')->orderBy('day_of_week', 'ASC')->orderBy('start_time', 'ASC')->get();

        return view('student_course_list', ['userStudentCourseSchedules' => $userStudentCourseSchedules]);
    }

    public function addStudentCourseDayScreen($studentCourseID)
    {
        $userID = Auth::id();

        $studentCourseDetails = StudentCourse::where('id', $studentCourseID)->get();

        if (sizeof($studentCourseDetails) > 0) {
            $checkstudentAccess = StudentAcess::where('user_id', $userID)->where('student_detail_id', $studentCourseDetails[0]->student_detail_id)->get();
            if (sizeof($checkstudentAccess) > 0) {
                $studentDetails = StudentDetail::where('id', $studentCourseDetails[0]->student_detail_id)->get();

                $curentSchedule = StudentCourseSchedule::with('student_course')->where('student_course_id', $studentCourseID)->orderBy('student_course_id', 'ASC')->orderBy('day_of_week', 'ASC')->orderBy('start_time', 'ASC')->get();

                return view('student_course_day_add', ['userCourses' => $curentSchedule, 'userStudents' => $studentDetails,]);
            }
        }

        return view('student_course_day_add', ['userCourses' => [], 'userStudents' => [],]);
    }

    public function addStudentCourseDaySave(Request $request, $studentCourseID)
    {
        $userID = Auth::id();

        $validated = $request->validate([
            'day_of_week' => 'required|integer|min:1|max:7',
            'start_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:1',
        ], [
            'day_of_week.required' => 'Kurs Günü seçim alanı boş bırakılamaz',
            'start_time.required' => 'Kurs Ücreti alanı boş bırakılamaz',
            'duration.required' => 'Kurs Süresi alanı boş bırakılamaz',
        ]);


        $studentCourseScheduleLine = [
            'student_course_id' => $studentCourseID,
            'day_of_week' => $validated["day_of_week"],
            'start_time' => $validated["start_time"],
            'duration' => $validated["duration"],
        ];

        $newStudentCourseSchedule = StudentCourseSchedule::create($studentCourseScheduleLine);

        $studentCourseDetails = StudentCourse::where('id', $studentCourseID)->get();

        if (sizeof($studentCourseDetails) > 0) {
            $checkstudentAccess = StudentAcess::where('user_id', $userID)->where('student_detail_id', $studentCourseDetails[0]->student_detail_id)->get();
            if (sizeof($checkstudentAccess) > 0) {
                $studentDetails = StudentDetail::where('id', $studentCourseDetails[0]->student_detail_id)->get();

                $curentSchedule = StudentCourseSchedule::with('student_course')->where('student_course_id', $studentCourseID)->orderBy('student_course_id', 'ASC')->orderBy('day_of_week', 'ASC')->orderBy('start_time', 'ASC')->get();

                return view('student_course_day_add', ['userCourses' => $curentSchedule, 'userStudents' => $studentDetails,]);
            }
        }

        return view('student_course_day_add', ['userCourses' => [], 'userStudents' => [],]);
    }

    public function addStudentAuthorityScreen($studentID)
    {

        $userID = Auth::id();

        $studentDetails = StudentDetail::where('id', $studentID)->get();

        $studenAuthorities = StudentAcess::where('student_detail_id', $studentID)->get();


        return view('student_authority_list', ['studentDetails' => $studentDetails, 'studenAuthorities' => $studenAuthorities]);
    }

    public function addStudentAuthoritySave(Request $request, $studentID)
    {
        $userID = Auth::id();

        $userStudents = StudentAcess::where('user_id', $userID)->get();

        if (sizeof($userStudents) > 0) {
            $validated = $request->validate([
                'user_email' => 'required|email',
            ]);

            $checkUserData = User::where('email', $validated["user_email"])->get();
            if (sizeof($checkUserData) > 0) {
                $authorisedUserID = $checkUserData[0]->id;

                $studentAccessLine = [
                    'student_detail_id' => $authorisedUserID,
                    'day_of_week' => $studentID,
                ];

                $newSudentAccess = StudentAcess::create($studentAccessLine);

            } else {
            }
        }

          $userID = Auth::id();

        $studentDetails = StudentDetail::where('id', $studentID)->get();

        $studenAuthorities = StudentAcess::where('student_detail_id', $studentID)->get();


        return view('student_authority_list', ['studentDetails' => $studentDetails, 'studenAuthorities' => $studenAuthorities]);
    }
}
