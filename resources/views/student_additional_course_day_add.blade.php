<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Yeni Kurs Saati Bilgileri
        </h2>
    </x-slot>
    @if ($errors->any())
    <div class="container">
        <div class="row p-3">
            <div class="col-12 p-5">
                <ul class="pl-4 pr-4 pt-2 pb-2 bg-red-100">
                    @foreach ($errors->all() as $error)
                    <li class="mt-2 mb-2 text-red-500">
                        {{ $error }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif
    <div class="container pt-5">
        <div class="row">
            <div class="col-12 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @if (sizeof($userCourses)>0)
                <div class="row">
                    <form action="{{ route('add_additional_student_course.save', $userCourses[0]->student_course_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row p-3">
                            <div class="col-md-4 col-12">
                                <div class="row">
                                    <div class="col-5 m-auto">
                                        <b>Öğrenci:</b>
                                    </div>
                                    <div class="col-7 m-auto">
                                        {{$userStudents[0]->student_name}} {{$userStudents[0]->student_surname}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="row">
                                    <div class="col-5 m-auto">
                                        <b>Kurs:</b>
                                    </div>
                                    <div class="col-7 m-auto">
                                        {{ $userCourses[0]->student_course->course->course_topic }} ({{
                                        $userCourses[0]->student_course->course->course_name }})
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="row">
                                    <div class="col-5 m-auto">
                                        <b>Kurs Ücreti:</b>
                                    </div>
                                    <div class="col-7 m-auto">
                                        {{ $studentCourseDetails[0]->course_price }} ({{
                                        $studentCourseDetails[0]->course_price_type }})
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row p-3">
                            <div class="col-md-3 col-6">
                                <div class="row">
                                    <div class="col-5 m-auto">
                                        <b>Kurs Günü:</b>
                                    </div>
                                    <div class="col-7 m-auto">
                                        <input type="date" name="additional_course_date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="row">
                                    <div class="col-6 m-auto">
                                        <b>Kurs Saati:</b>
                                    </div>
                                    <div class="col-6 m-auto">
                                        <input type="time" class="form-control" id="start_time" name="start_time"
                                            value="{{ old('start_time')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="row">
                                    <div class="col-6 m-auto">
                                        <b>Kurs Süresi (dk.): </b>
                                    </div>
                                    <div class="col-6 m-auto">
                                        <input type="number" class="form-control" id="duration" name="duration"
                                            value="{{ old('duration')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="row">
                                    <div class="col-6 m-auto">
                                        <b>Kurs Ücreti: </b>
                                    </div>
                                    <div class="col-6 m-auto">
                                        <input type="number" class="form-control" id="course_price" name="course_price"
                                            value="{{ old('course_price')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row p-3">
                            <div class="offset-md-4 col-md-4 col-12">
                                <button type="submit" class="btn btn-success" style="width: 100%">
                                    Kaydet
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
    @php
    $days = [
    1 => 'Pazartesi',
    2 => 'Salı',
    3 => 'Çarşamba',
    4 => 'Perşembe',
    5 => 'Cuma',
    6 => 'Cumartesi',
    7 => 'Pazar',
    ];
    @endphp
     <div class="container">
        <div class="row p-3">
            <div class="col-12 text-center">
                <h4 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Katılım Detayları</h4>
            </div>
        </div>
     </div>
    <div class="container">
        <div class="row p-3">
            <div class="col-12 p-5">
                <div class="row p-3">
                    <div class="col-12 text-center">
                        @if(sizeof($attendanceList)>0)
                        <table id="student_course_details_list_table" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Kurs Günü</th>
                                    <th>Kurs Saati</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendanceList as $userCourse)
                                @php
                                     $startTime = \Carbon\Carbon::createFromFormat('H:i:s', $userCourse->course_time);
                                     $endTime  = \Carbon\Carbon::createFromFormat('H:i:s', $userCourse->course_time)->addMinutes($userCourse->attended_duration);
;
                                @endphp
                                <tr onclick="">
                                    @php
                                    error_log('1');
                                    @endphp
                                    <td>{{ \Carbon\Carbon::parse($userCourse->course_date)->format('d/m/Y') }} {{ $days[\Carbon\Carbon::parse($userCourse->course_date)->dayOfWeek] }} 
                                    </td>
                                    <td>{{ $startTime->format('H:i') }} - {{ $endTime->format('H:i') }} </td>
                                    
                                </tr>
                                @php
                                error_log('4');
                                @endphp
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <h4>Sizin listenize kayıtlı öğrenci yok.</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>