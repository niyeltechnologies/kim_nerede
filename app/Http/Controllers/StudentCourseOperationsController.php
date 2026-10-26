<?php

namespace App\Http\Controllers;

use App\Mail\UserContact;
use App\Models\Course;
use App\Models\StudentAcess;
use App\Models\StudentCourse;
use App\Models\StudentCoursePayment;
use App\Models\StudentCourseSchedule;
use App\Models\StudentCourseStatement;
use App\Models\StudentDetail;
use App\Models\User;
use App\Models\UserInvite;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class StudentCourseOperationsController extends Controller
{
    //
    public function getDashboard()
    {

        $userID = Auth::id();

        $userStudents = StudentAcess::where('user_id', $userID)->get('student_detail_id');

        $today = Carbon::today();

        $userStudentCourses = StudentCourse::whereIn('student_detail_id', $userStudents)->get('id');

        $userStudentCourseSchedules = StudentCourseSchedule::with('student_course')->whereIn('student_course_id', $userStudentCourses)->orderBy('start_time', 'ASC')->orderBy('day_of_week', 'ASC')->get();

        $todaysCourses = StudentCourseSchedule::with('student_course')->whereIn('student_course_id', $userStudentCourses)->where('day_of_week', $today->isoWeekday())->orderBy('start_time', 'ASC')->get();

        $attendanceList = StudentCourseStatement::with('student_course')->whereIn('student_course_id', $userStudentCourses)->where('attended', 0)->orderBy('course_date', 'ASC')->orderBy('course_time', 'ASC')->get();

        $useremail = User::where('id', $userID)->get();

        $userStudentAuthorityRequests = UserInvite::with('student_detail')->where('user_email', $useremail[0]->email)->get();

        return view('dashboard', ['userStudentCourseSchedules' => $userStudentCourseSchedules, 'userStudentAuthorityRequests' => $userStudentAuthorityRequests, 'todaysCourses' => $todaysCourses, 'attendanceList' => $attendanceList]);
    }

    public function getWeeklySchedule()
    {

        $userID = Auth::id();

        $userStudents = StudentAcess::where('user_id', $userID)->get('student_detail_id');

        $today = Carbon::today();

        $userStudentCourses = StudentCourse::whereIn('student_detail_id', $userStudents)->get('id');

        $userStudentCourseSchedules = StudentCourseSchedule::with('student_course')->whereIn('student_course_id', $userStudentCourses)->orderBy('start_time', 'ASC')->orderBy('day_of_week', 'ASC')->get();

        $todaysCourses = StudentCourseSchedule::with('student_course')->whereIn('student_course_id', $userStudentCourses)->where('day_of_week', $today->isoWeekday())->orderBy('start_time', 'ASC')->get();

        $attendanceList = StudentCourseStatement::with('student_course')->whereIn('student_course_id', $userStudentCourses)->where('attended', 0)->orderBy('course_date', 'ASC')->orderBy('course_time', 'ASC')->get();

        $useremail = User::where('id', $userID)->get();

        $userStudentAuthorityRequests = UserInvite::with('student_detail')->where('user_email', $useremail[0]->email)->get();

        return view('weekly_schedule', ['userStudentCourseSchedules' => $userStudentCourseSchedules, 'userStudentAuthorityRequests' => $userStudentAuthorityRequests, 'todaysCourses' => $todaysCourses, 'attendanceList' => $attendanceList]);
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

        return $this->getDashboard();
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
            'course_name.required' => 'Aktivite (Hoca) İsmi alanı boş bırakılamaz',
            'course_topic.required' => 'Aktivite Konusu alanı boş bırakılamaz',
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
            'course_id.required' => 'Aktivite seçim alanı boş bırakılamaz',
            'course_price.required' => 'Aktivite Ücreti alanı boş bırakılamaz',
            'course_price_type.required' => 'Ücret Türü alanı boş bırakılamaz',
            'day_of_week.required' => 'Aktivite Günü seçim alanı boş bırakılamaz',
            'start_time.required' => 'Aktivite Ücreti alanı boş bırakılamaz',
            'duration.required' => 'Aktivite Süresi alanı boş bırakılamaz',
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
            'day_of_week.required' => 'Aktivite Günü seçim alanı boş bırakılamaz',
            'start_time.required' => 'Aktivite Ücreti alanı boş bırakılamaz',
            'duration.required' => 'Aktivite Süresi alanı boş bırakılamaz',
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

    public function addAdditionalStudentCourseScreen($studentCourseID)
    {
        $userID = Auth::id();

        $studentCourseDetails = StudentCourse::where('id', $studentCourseID)->get();
        $attendanceList = StudentCourseStatement::with('student_course')->where('student_course_id', $studentCourseID)->where('attended', 1)->orderBy('course_date', 'ASC')->orderBy('course_time', 'ASC')->get();

        if (sizeof($studentCourseDetails) > 0) {
            $checkstudentAccess = StudentAcess::where('user_id', $userID)->where('student_detail_id', $studentCourseDetails[0]->student_detail_id)->get();
            if (sizeof($checkstudentAccess) > 0) {
                $studentDetails = StudentDetail::where('id', $studentCourseDetails[0]->student_detail_id)->get();

                $curentSchedule = StudentCourseSchedule::with('student_course')->where('student_course_id', $studentCourseID)->orderBy('student_course_id', 'ASC')->orderBy('day_of_week', 'ASC')->orderBy('start_time', 'ASC')->get();



                return view('student_additional_course_day_add', ['userCourses' => $curentSchedule, 'userStudents' => $studentDetails, 'studentCourseDetails' => $studentCourseDetails, 'attendanceList' => $attendanceList]);
            }
        }

        return view('student_additional_course_day_add', ['userCourses' => [], 'userStudents' => [], 'studentCourseDetails' => []]);
    }

    public function addAdditionalStudentCourseSave(Request $request, $studentCourseID)
    {

        $userID = Auth::id();

        $studentCourseDetails = StudentCourse::where('id', $studentCourseID)->get();
        $attendanceList = StudentCourseStatement::with('student_course')->where('student_course_id', $studentCourseID)->where('attended', 1)->orderBy('course_date', 'ASC')->orderBy('course_time', 'ASC')->get();


        if (sizeof($studentCourseDetails) > 0) {
            $checkstudentAccess = StudentAcess::where('user_id', $userID)->where('student_detail_id', $studentCourseDetails[0]->student_detail_id)->get();

            if (sizeof($checkstudentAccess) > 0) {
                if (($request->course_price == 0) || ($request->course_price == null)) {

                    if ($studentCourseDetails[0]->course_price_type == '1') { // Aylık
                        $courseCharge = $studentCourseDetails[0]->course_price;
                    } else if ($studentCourseDetails[0]->course_price_type == '2') { // Saatlik
                        $courseCharge = $request->courseCharge * ($request->duration / 60);
                    } else if ($studentCourseDetails[0]->course_price_type == '3') { // Ders Başı
                        $courseCharge = $request->courseCharge;
                    } else {
                        $courseCharge = 0;
                    }

                    $newCourseStatementLine = [
                        'student_course_id' => $studentCourseID,
                        'course_date' => $request->additional_course_date,
                        'course_time' => $request->start_time,
                        'amount_due' => $courseCharge,
                        'amount_type' => $studentCourseDetails[0]->course_price_type,
                        'attended_duration' => $request->duration,
                        'attended' => 1,
                    ];
                    $newStatment = StudentCourseStatement::create($newCourseStatementLine);
                }

                if ($request->course_price == null) {
                    $coursePrice = 0;
                } else {
                    $coursePrice = $request->course_price;
                }

                $newCourseStatementLine = [
                    'student_course_id' => $studentCourseID,
                    'course_date' => $request->additional_course_date,
                    'course_time' => $request->start_time,
                    'amount_due' => $coursePrice,
                    'amount_type' => 4,
                    'attended_duration' => $request->duration,
                    'attended' => 1,
                ];


                $newStatment = StudentCourseStatement::create($newCourseStatementLine);

                $studentDetails = StudentDetail::where('id', $studentCourseDetails[0]->student_detail_id)->get();

                $curentSchedule = StudentCourseSchedule::with('student_course')->where('student_course_id', $studentCourseID)->orderBy('student_course_id', 'ASC')->orderBy('day_of_week', 'ASC')->orderBy('start_time', 'ASC')->get();

                return view('student_additional_course_day_add', ['userCourses' => $curentSchedule, 'userStudents' => $studentDetails, 'studentCourseDetails' => $studentCourseDetails, 'attendanceList' => $attendanceList]);
            }
        }

        return view('student_additional_course_day_add', ['userCourses' => [], 'userStudents' => [], 'studentCourseDetails' => []]);
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
            /*   if (sizeof($checkUserData) > 0) {
                $authorisedUserID = $checkUserData[0]->id;

                $studentAccessLine = [
                    'user_id' => $authorisedUserID,
                    'student_detail_id' => $studentID,
                ];

                $newSudentAccess = StudentAcess::create($studentAccessLine);
            } else {
             */
            $inviteCode = rand(10000, 99999);
            $userInviteLine = [
                'user_email' => $validated["user_email"],
                'student_detail_id' => $studentID,
                'invited_by' => $userID,
                'invite_code' => $inviteCode,
            ];

            $newUserInvite = UserInvite::create($userInviteLine);

            $studentDetails = StudentDetail::where('id',  $studentID)->get();

            $userDetails = User::where('id', $userID)->get();

            $maildata = [
                'name' => $studentDetails[0]->student_name . ' ' . $studentDetails[0]->student_surname,
                'approval_code' => $inviteCode,
                'sender' => $userDetails[0]->name,
            ];

            try {
                //      Mail::to($validated["user_email"])->queue(new UserContact($maildata));
                Mail::to($validated["user_email"])->send(new UserContact($maildata));
                Log::info('Email sent successfully', ['to' => $validated["user_email"]]);
            } catch (TransportExceptionInterface $e) {
                Log::error('Email failed to send', [
                    'to' => $validated["user_email"],
                    'error' => $e->getMessage(),
                ]);
                return back()->withErrors(['email' => 'E-posta gönderilemedi.']);
            }

            // }
        }

        $userID = Auth::id();

        $studentDetails = StudentDetail::where('id', $studentID)->get();

        $studenAuthorities = StudentAcess::where('student_detail_id', $studentID)->get();


        return view('student_authority_list', ['studentDetails' => $studentDetails, 'studenAuthorities' => $studenAuthorities]);
    }

    public function getStudentAuthorityList()
    {

        $userID = Auth::id();
        $useremail = User::where('id', $userID)->get();

        $userStudentAuthorityRequests = UserInvite::with('student_detail')->where('user_email', $useremail[0]->email)->get();

        return view('student_authority_request_list', ['userStudentAuthorityRequests' => $userStudentAuthorityRequests]);
    }

    public function addStudentAuthorityApproveScreen($requestID)
    {

        $userID = Auth::id();
        $useremail = User::where('id', $userID)->get();

        $userStudentAuthorityRequests = UserInvite::with('student_detail')->where('id', $requestID)->where('user_email', $useremail[0]->email)->get();

        return view('student_authority_approve', ['userStudentAuthorityRequests' => $userStudentAuthorityRequests]);
    }


    public function addStudentAuthorityApproveSave(Request $request, $requestID)
    {
        $userID = Auth::id();
        $useremail = User::where('id', $userID)->get();

        $userStudentAuthorityRequests = UserInvite::where('id', $requestID)->where('user_email', $useremail[0]->email)->where('invite_code', $request['invite_code'])->get();


        if (sizeof($userStudentAuthorityRequests) > 0) {
            $studentID = $userStudentAuthorityRequests[0]->student_detail_id;


            $studentAccessLine = [
                'user_id' => $userID,
                'student_detail_id' => $studentID,
            ];

            $newSudentAccess = StudentAcess::create($studentAccessLine);

            $userID = Auth::id();

            $studentDetails = StudentDetail::where('id', $studentID)->get();

            $studenAuthorities = StudentAcess::where('student_detail_id', $studentID)->get();


            return view('student_authority_list', ['studentDetails' => $studentDetails, 'studenAuthorities' => $studenAuthorities]);
        } else {
            return redirect()->back()->withErrors(['msg' => 'Öğrenci yetki talebi kodunuz geçerli değil!']);
        }
    }

    public function coursescheduleCourseDayReject($studentAttendaceID)
    {
        $userID = Auth::id();

        $userStudents = StudentAcess::where('user_id', $userID)->pluck('student_detail_id');

        $userStudentCourses = StudentCourse::whereIn('student_detail_id', $userStudents)->pluck('id');


        $attendanceList = StudentCourseStatement::find($studentAttendaceID);

        if ($userStudentCourses->contains($attendanceList->student_course_id)) {
            $attendanceList = StudentCourseStatement::find($studentAttendaceID);
            $attendanceList->amount_due = 0;
            $attendanceList->attended_duration = 0;
            $attendanceList->attended = -1;
            $attendanceList->save();
        } else {
            echo $attendanceList;
            echo "Not in array: User ID->" . $userID . " Attendance ID->" . $studentAttendaceID;
        }

        return $this->getDashboard();
    }

    public function coursescheduleCourseDayAccept(Request $request, $studentAttendaceID)
    {
        $userID = Auth::id();

        $userStudents = StudentAcess::where('user_id', $userID)->pluck('student_detail_id');

        $userStudentCourses = StudentCourse::whereIn('student_detail_id', $userStudents)->pluck('id');


        $attendanceList = StudentCourseStatement::find($studentAttendaceID);

        if ($userStudentCourses->contains($attendanceList->student_course_id)) {
            echo $attendanceList->student_course_id . "<br>";
            $studentCourseDetails = StudentCourse::where('id', $attendanceList->student_course_id)->get();
            if ($studentCourseDetails[0]->course_price_type == '1') { // Aylık
                $courseCharge = 0;
            } else if ($studentCourseDetails[0]->course_price_type == '2') { // Saatlik
                $courseCharge = $request->courseCharge * ($attendanceList->attended_duration / 60);
            } else if ($studentCourseDetails[0]->course_price_type == '3') { // Ders Başı
                $courseCharge = $request->courseCharge;
            } else {
                $courseCharge = 0;
            }

            $attendanceList->amount_due = $courseCharge;
            $attendanceList->attended = 1;
            $attendanceList->save();
        } else {
            echo $attendanceList->student_course_id;
            print_r($userStudentCourses);
            echo "Not in array: User ID->" . $userID . " Attendance ID->" . $studentAttendaceID;
        }

        return $this->getDashboard();
    }

    public function getPaymentCourseList()
    {

        $userID = Auth::id();

        $userStudents = StudentAcess::where('user_id', $userID)->pluck('student_detail_id');

        $userStudentCourses = StudentCourse::with('student_detail', 'course')->whereIn('student_detail_id', $userStudents)->get();

        $courseStatement = [];

        foreach ($userStudentCourses as $currStudentCourse) {
            $courseBasedCharges = 0;
            $monthlyCharges = [];
            $hourlyCharges = 0;
            $additionalCharges = 0;

            $studentCourseCharges = StudentCourseStatement::where('student_course_id', $currStudentCourse->id)->get();

            foreach ($studentCourseCharges as $currCourseCharge) {
                if ($currCourseCharge->amount_type == 4) {
                    $additionalCharges = $additionalCharges + $currCourseCharge->amount_due;
                } else if ($currCourseCharge->amount_type == 1) {
                    if (isset($monthlyCharges[substr($currCourseCharge->course_date, 0, 7)])) {
                        if ($currCourseCharge->amount_due > $monthlyCharges[substr($currCourseCharge->course_date, 0, 7)]) {
                            $monthlyCharges[substr($currCourseCharge->course_date, 0, 7)] = $currCourseCharge->amount_due;
                        }
                    } else {
                        $monthlyCharges[substr($currCourseCharge->course_date, 0, 7)] = $currCourseCharge->amount_due;
                    }
                } else if ($currCourseCharge->amount_type == 2) {
                    $hourlyCharges = $hourlyCharges + $currCourseCharge->amount_due;
                } else if ($currCourseCharge->amount_type == 3) {
                    $courseBasedCharges = $courseBasedCharges + $currCourseCharge->amount_due;
                }
            }


            $totalPaid = StudentCoursePayment::where('student_course_id', $currStudentCourse->id)->sum('payment_amount');

            $courseDebtData =  [
                'student_course_id' => $currStudentCourse->id,
                'course_name' => $currStudentCourse->course->course_name,
                'course_topic' => $currStudentCourse->course->course_topic,
                'student_name' => $currStudentCourse->student_detail->student_name . ' ' . $currStudentCourse->student_detail->student_surname,
                'courseBasedCharges' => $courseBasedCharges,
                'monthlyCharges' => $monthlyCharges,
                'hourlyCharges' => $hourlyCharges,
                'additionalCharges' => $additionalCharges,
                'totalPayments' => $totalPaid,
            ];

            array_push($courseStatement, $courseDebtData);
        }


        return view('payment_course_list', ['courseDebtData' => $courseStatement]);
    }

    public function addCoursePaymentScreen($studentCourseID)
    {
        $studentCourseDetails = StudentCourse::where('id', $studentCourseID)->get();
        $studentCoursePayments = StudentCoursePayment::where('student_course_id', $studentCourseID)->get();


        $courseBasedCharges = 0;
        $monthlyCharges = [];
        $hourlyCharges = 0;
        $additionalCharges = 0;

        $studentCourseCharges = StudentCourseStatement::where('student_course_id', $studentCourseID)->get();

        foreach ($studentCourseCharges as $currCourseCharge) {
            if ($currCourseCharge->amount_type == 4) {
                $additionalCharges = $additionalCharges + $currCourseCharge->amount_due;
            } else if ($currCourseCharge->amount_type == 1) {
                if (isset($monthlyCharges[substr($currCourseCharge->course_date, 0, 7)])) {
                    if ($currCourseCharge->amount_due > $monthlyCharges[substr($currCourseCharge->course_date, 0, 7)]) {
                        $monthlyCharges[substr($currCourseCharge->course_date, 0, 7)] = $currCourseCharge->amount_due;
                    }
                } else {
                    $monthlyCharges[substr($currCourseCharge->course_date, 0, 7)] = $currCourseCharge->amount_due;
                }
            } else if ($currCourseCharge->amount_type == 2) {
                $hourlyCharges = $hourlyCharges + $currCourseCharge->amount_due;
            } else if ($currCourseCharge->amount_type == 3) {
                $courseBasedCharges = $courseBasedCharges + $currCourseCharge->amount_due;
            }
        }

        $totalDebt = $courseBasedCharges + $hourlyCharges + $additionalCharges;

        foreach ($monthlyCharges as  $key => $value) {
            $totalDebt = $totalDebt + $value;
        }

        $totalPaid = StudentCoursePayment::where('student_course_id', $studentCourseID)->sum('payment_amount');



        return view('course_payment_add', ['studentCourseDetails' => $studentCourseDetails, 'studentCoursePayments' => $studentCoursePayments, 'totalDebt' => $totalDebt, 'totalPaid' => $totalPaid]);
    }

    public function addCoursePaymentSave(Request $request, $studentCourseID)
    {
        $userID = Auth::id();

        $validated = $request->validate([
            'payment_amount' => 'required|numeric',
            'payment_date' => 'required|date_format:Y-m-d',
        ], [
            'payment_amount.required' => 'Ödeme miktarı alanı boş bırakılamaz',
            'payment_date.required' => 'Ödeöe tarihi gg/aa/yyyy şeklinde girilmelidir.',
        ]);

        $coursePaymentLine = [
            'student_course_id' => $studentCourseID,
            'payment_amount' => $validated["payment_amount"],
            'payment_date' => $validated["payment_date"],
        ];

        $newPayment = StudentCoursePayment::create($coursePaymentLine);

        return $this->getPaymentCourseList();
    }
}
