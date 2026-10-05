<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kurs Programı İşlemleri') }}
        </h2>
    </x-slot>

    <div class="container pt-5">
        <div class="row">
            <div class="col-12 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="row">

                    <div class="col-4 p-6 text-gray-900 dark:text-gray-100">
                        Kurs Programı:
                    </div>
                    <div class="offset-md-6 col-2 p-6 text-gray-900 dark:text-gray-100">
                        <button onclick="window.location.href='{{ route('add_student_course.show') }}'" type="button"
                            class="btn btn-success">
                            Yeni Kurs Ekle
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row p-3">
            <div class="col-12 p-5">
                <div class="row p-3">
                    <div class="col-12 text-center">
                        @if(sizeof($userStudentCourseSchedules)>0)
                        <table id="student_courses_list_table" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Kurs İsmi</th>
                                    <th>Öğrenci İsmi</th>
                                    <th>Ücret</th>
                                    <th>Günler</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                 $days = [
                                1 => 'Pzt.',
                                2 => 'Sal.',
                                3 => 'Çar.',
                                4 => 'Per.',
                                5 => 'Cum.',
                                6 => 'Ctesi.',
                                7 => 'Paz.',
                            ];
                                $currCourseID = -1;
                                $courseDays = '' ;
                                foreach($userStudentCourseSchedules as $userStudentCourse)
                                {
                                if( $currCourseID != $userStudentCourse->student_course_id){
                                if($currCourseID != -1){
                                echo '<td>' . $courseDays . '</td>';
                                echo '<td>';
                                echo '    <a href="'. route('add_student_course_day.show', $currCourseID) .'" type="button" class="btn btn-success">';
                                echo '        Yeni Kurs Günü Ekle';
                                echo '    </a></td>';
                                echo '</tr>';
                                $courseDays = '' ;
                                }

                                $courseDays = $courseDays . $days[$userStudentCourse->day_of_week] . ' ' ; 
                                $currCourseID = $userStudentCourse->student_course_id ;
                                @endphp
                                <tr onclick="">

                                    <td>{{ $userStudentCourse->student_course->course->course_name }} ({{
                                        $userStudentCourse->student_course->course->course_topic }})
                                    </td>
                                    <td>
                                        {{ $userStudentCourse->student_course->student_detail->student_name }} {{
                                        $userStudentCourse->student_course->student_detail->student_surname }}
                                    </td>
                                    <td>{{ $userStudentCourse->student_course->course_price }} /

                                        @if ($userStudentCourse->student_course->course_price_type == 1)
                                        Ay
                                        @else
                                        @if ($userStudentCourse->student_course->course_price_type == 2)
                                        Saat
                                        @else
                                        Ders
                                        @endif
                                        @endif

                                    </td>
                                        @php
                                        } else {
                                            $courseDays = $courseDays . $days[$userStudentCourse->day_of_week] . ' ' ; 
                                        }
                                }
                                echo '<td>' . $courseDays . '</td>';
                                echo '<td>';
                                echo '    <a href="'. route('add_student_course_day.show', $currCourseID) .'" type="button" class="btn btn-success">';
                                echo '        Yeni Kurs Günü Ekle';
                                echo '    </a></td>';
                                echo '</tr>';
                                        error_log('4');
                                        @endphp

                            </tbody>
                        </table>
                        @else
                        <h4>Sizin listenize kayıtlı kurs programı yok.</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script type="text/javascript">
    new DataTable('#student_courses_list_table', {});
</script>