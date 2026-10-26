<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Ödeme Bilgileri
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
                @if (sizeof($studentCourseDetails)>0)
                <div class="row">
                    <form action="{{ route('add_course_payment.save', $studentCourseDetails[0]->id) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row p-3">
                            <div class="col-md-4 col-12">
                                <div class="row">
                                    <div class="col-5 m-auto">
                                        <b>Katılımcı:</b>
                                    </div>
                                    <div class="col-7 m-auto">
                                        {{$studentCourseDetails[0]->student_detail->student_name}} {{$studentCourseDetails[0]->student_detail->student_surname}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="row">
                                    <div class="col-5 m-auto">
                                        <b>Aktivite:</b>
                                    </div>
                                    <div class="col-7 m-auto">
                                        {{ $studentCourseDetails[0]->course->course_topic }} ({{
                                        $studentCourseDetails[0]->course->course_name }})
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="row">
                                    <div class="col-5 m-auto">
                                        <b>Toplam Borç:</b>
                                    </div>
                                    <div class="col-7 m-auto">
                                        {{ $totalDebt - $totalPaid }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row p-3">
                            <div class="col-md-4 col-6">
                                <div class="row">
                                    <div class="col-5 m-auto">
                                        <b>Ödeme Tarihi:</b>
                                    </div>
                                    <div class="col-7 m-auto">
                                        <input type="date" name="payment_date" class="form-control">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 col-6">
                                <div class="row">
                                    <div class="col-6 m-auto">
                                        <b>Ödeme Miktarı: </b>
                                    </div>
                                    <div class="col-6 m-auto">
                                        <input type="number" class="form-control" id="payment_amount" name="payment_amount"
                                            value="{{ old('payment_amount')}}">
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
                <h4 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Geçmiş Ödeme Detayları</h4>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row p-3">
            <div class="col-12 p-5">
                <div class="row p-3">
                    <div class="col-12 text-center">
                        @if(sizeof($studentCoursePayments)>0)
                        <table id="student_course_details_list_table" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Ödeme Tarihi</th>
                                    <th>Ödeme Tutarı</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($studentCoursePayments as $studentCoursePayment)
                                <tr onclick="">
                                    @php
                                    error_log('1');
                                    @endphp
                                    <td>{{ \Carbon\Carbon::parse($studentCoursePayment->payment_date)->format('d/m/Y') }} 
                                    </td>
                                    <td>{{ $studentCoursePayment->payment_amount }} </td>

                                </tr>
                                @php
                                error_log('4');
                                @endphp
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <h4>Bu aktivite için ödeme kaydınız yok.</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>