<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Öğrenci İşlemleri') }}
        </h2>
    </x-slot>

    <div class="container pt-5">
        <div class="row">
            <div class="col-12 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="row">
                <div class="col-4 p-6 text-gray-900 dark:text-gray-100">
                    Öğrenciler:
                </div>
                 <div class="offset-md-6 col-2 p-6 text-gray-900 dark:text-gray-100">
                   <button onclick="window.location.href='{{ route('add_student.show') }}'" type="button" class="btn btn-success">
                    Öğrenci Ekle
                   </button>
                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container pt-3">
        <div class="row">
            <div class="col-12 text-center">
                <h2>Kayıtlı Öğrenci Listesi</h2>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row p-3">
            <div class="col-12 p-5">
                <div class="row p-3">
                    <div class="col-12 text-center">
                        @if(sizeof($userStudentAuthorityRequests)>0)
                        <table id="student_details_list_table" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Öğrenci Adı</th>
                                    <th>Öğrenci Soyadı</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userStudentAuthorityRequests as $userStudent)
                                <tr
                                    onclick="">
                                    @php
                                    error_log('1');
                                    @endphp
                                    <td>{{ $userStudent->student_detail->student_name }}
                                    </td>
                                    <td>{{ $userStudent->student_detail->student_surname }}</td>
                                    <td>
                                        <a class="btn btn-primary" href="{{route('add_student_authority.show', $userStudent->id)}}">Kabul Et</a>
                                        <a class="btn btn-danger">Sil</a>
                                    </td>
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

<script type="text/javascript">
    new DataTable('#student_details_list_table', {});
</script>