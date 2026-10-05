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
                <div class="row">
                    <form action="{{ route('add_student_course.save') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row p-3">
                            <div class="col-md-6 col-12">
                                <div class="row">
                                    <div class="col-5 m-auto">
                                        <b>Öğrenci:</b>
                                    </div>
                                    <div class="col-7 m-auto">
                                        <select class="form-control" name="student_detail_id">
                                            @foreach($userStudents as $userStudent)
                                            <option value="{{ $userStudent->id }}"> {{ $userStudent->student_detail->student_name }} {{ $userStudent->student_detail->student_surname }}
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="row">
                                    <div class="col-5 m-auto">
                                        <b>Kurs:</b>
                                    </div>
                                    <div class="col-7 m-auto">
                                        <select class="form-control" name="course_id">
                                            @foreach($userCourses as $userCourse)
                                            <option value="{{ $userCourse->id }}"> {{ $userCourse->course_name }} ({{ $userCourse->course_topic }})
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row p-3">
                            <div class="col-md-4 col-12">
                                <div class="row">
                                    <div class="col-5 m-auto">
                                        <b>Kurs Günü:</b>
                                    </div>
                                    <div class="col-7 m-auto">
                                       <select class="form-control" name="day_of_week">
                                            <option value="1"> Pazartesi
                                            <option value="2"> Salı
                                            <option value="3"> Çarşamba
                                            <option value="4"> Perşembe
                                            <option value="5"> Cuma
                                            <option value="6"> Cumartesi
                                            <option value="7"> Pazar
                                        </select>
                                    </div>
                                </div>
                            </div><div class="col-md-4 col-12">
                                <div class="row">
                                    <div class="col-5 m-auto">
                                        <b>Kurs Saati:</b>
                                    </div>
                                    <div class="col-7 m-auto">
                                        <input type="time" class="form-control" id="start_time"
                                            name="start_time" value="{{ old('start_time')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                 <div class="row">
                                    <div class="col-5 m-auto">
                                        <b>Kurs Süresi (dk.): </b>
                                    </div>
                                    <div class="col-7 m-auto">
                                        <input type="number" class="form-control" id="duration"
                                            name="duration" value="{{ old('duration')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row p-3">
                            <div class="col-md-4 col-12">
                                <div class="row">
                                    <div class="col-5 m-auto">
                                        <b>Kurs Ücreti:</b>
                                    </div>
                                    <div class="col-7 m-auto">
                                        <input type="number" class="form-control" id="course_price"
                                            name="course_price" value="{{ old('course_price')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="row">
                                    <div class="col-5 m-auto">
                                        <b>Ücret Türü:</b>
                                    </div>
                                    <div class="col-7 m-auto">
                                       <select class="form-control" name="course_price_type">
                                            <option value="1"> Aylık
                                            <option value="2"> Saatlik
                                            <option value="3"> Ders Başı
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
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
            </div>
        </div>
    </div>


</x-app-layout>