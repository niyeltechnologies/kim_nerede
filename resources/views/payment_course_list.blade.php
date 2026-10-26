<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ödeme İşlemleri') }}
        </h2>
    </x-slot>

    <div class="container pt-5">
        <div class="row">
            <div class="col-12 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="row">
                    
                <div class="col-4 p-6 text-gray-900 dark:text-gray-100">
                    Bakiyeler:
                </div>
                 <div class="offset-md-6 col-2 p-6 text-gray-900 dark:text-gray-100">
                  
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
                        @if(sizeof($courseDebtData)>0)
                        <table id="student_courses_balance_list_table" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Aktivite (Hoca) Adı</th>
                                    <th>Aktivite Konusu</th>
                                    <th>Katılımcı Adı</th>
                                    <th>Toplam Ücret</th>
                                    <th>Toplam Ödeme</th>
                                    <th>Toplam Borç</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($courseDebtData as $userCourse)
                                @php
                                    $totalDebt = $userCourse["courseBasedCharges"] + $userCourse["hourlyCharges"] + $userCourse["additionalCharges"] ;
                                    foreach ($userCourse["monthlyCharges"] as $key => $value) {
                                        $totalDebt = $totalDebt + $value ;
                                    }

                                    $balanceDue = $totalDebt - $userCourse["totalPayments"] ;
                                @endphp
                                <tr
                                    onclick="">
                                    @php
                                    error_log('1');
                                    @endphp
                                    <td>{{ $userCourse["course_name"] }}
                                    </td>
                                    <td>{{ $userCourse["course_topic"] }}
                                    </td>
                                    <td>{{ $userCourse["student_name"] }}
                                    </td>
                                    <td>{{ number_format($totalDebt,2) }}</td>
                                    <td>{{ $userCourse["totalPayments"] }} </td>
                                    <td>{{ number_format($balanceDue,2) }}</td>
                                    <td><button onclick="window.location.href='{{ route('add_course_payment.show', $userCourse['student_course_id']) }}'" type="button" class="btn btn-success">Ödeme Ekle</button></td>
                                </tr>
                                @php
                                error_log('4');
                                @endphp
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <h4>Sizin listenize kayıtlı aktivite yok.</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script type="text/javascript">
    new DataTable('#student_courses_balance_list_table', {});
</script>