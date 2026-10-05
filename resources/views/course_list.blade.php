<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kurs İşlemleri') }}
        </h2>
    </x-slot>

    <div class="container pt-5">
        <div class="row">
            <div class="col-12 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="row">
                    
                <div class="col-4 p-6 text-gray-900 dark:text-gray-100">
                    Kurslar:
                </div>
                 <div class="offset-md-6 col-2 p-6 text-gray-900 dark:text-gray-100">
                   <button onclick="window.location.href='{{ route('add_course.show') }}'" type="button" class="btn btn-success">
                    Kurs Ekle
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
                        @if(sizeof($userCourses)>0)
                        <table id="student_courses_list_table" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Kurs (Hoca) Adı</th>
                                    <th>Kurs Konusu</th>
                                    <th>İletişim No.</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userCourses as $userCourse)
                                <tr
                                    onclick="">
                                    @php
                                    error_log('1');
                                    @endphp
                                    <td>{{ $userCourse->course_name }}
                                    </td>
                                    <td>{{ $userCourse->course_topic }}
                                    </td>
                                    <td>{{ $userCourse->instructor_phone }}
                                    </td>
                                    <td><button type="button" class="btn btn-primary">Düzenle</button></td>
                                    <td><button type="button" class="btn btn-danger">Sil</button></td>
                                </tr>
                                @php
                                error_log('4');
                                @endphp
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <h4>Sizin listenize kayıtlı kurs yok.</h4>
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