<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Öğrenci Yetki Bilgileri
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
                    <form action="{{ route('add_student_authority.save', $studentDetails[0]->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row p-3">
                            <div class="col-md-6 col-12">
                                <div class="row">
                                    <div class="col-5 m-auto">
                                        <b>Öğrenci:</b>
                                    </div>
                                    <div class="col-7 m-auto">
                                        {{$studentDetails[0]->student_name}} {{$studentDetails[0]->student_surname}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="row">
                                    <div class="col-5 m-auto">
                                        <b>Yetki Verilecek e-mail Adresi: </b>
                                    </div>
                                    <div class="col-7 m-auto">
                                        <input type="email" class="form-control" id="user_email" name="user_email"
                                            value="{{ old('user_email')}}">
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
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row p-3">
            <div class="col-12 p-5">
                <div class="row p-3">
                    <div class="col-12 text-center">
                        @if(sizeof($studenAuthorities)>1)
                        <table id="student_authority_list_table" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>İsim</th>
                                    <th>e-mail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($studenAuthorities as $studenAuthority)
                                
                                <tr onclick="">
                                    @php
                                    error_log('1');
                                    @endphp
                                    <td>{{ $studenAuthority->user->name }}
                                    </td>
                                    <td>{{ $studenAuthority->user->email }}
                                    </td>
                                    <td><button type="button" class="btn btn-danger">Sil</button></td>
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
    new DataTable('#student_authority_list_table', {});
</script>