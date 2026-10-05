<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Yeni Öğrenci Bilgileri
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
                    <form action="{{ route('add_student.save') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row p-3">                            
                            <div class="col-md-6 col-12">
                                <div class="row">
                                    <div class="col-5 m-auto">
                                        <b>Öğrenci İsmi:</b>
                                    </div>
                                    <div class="col-7 m-auto">
                                      <input type="text" class="form-control" id="student_name"
                                        name="student_name" value="{{ old('student_name')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="row">
                                    <div class="col-5 m-auto">
                                        <b>Öğrenci Soyismi:</b>
                                    </div>
                                    <div class="col-7 m-auto">
                                       <input type="text" class="form-control" id="student_surname"
                                        name="student_surname" value="{{ old('student_surname')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row p-3">                            
                            <div class="col-md-6 col-12">
                                <div class="row">
                                    <div class="col-5 m-auto">
                                        <b>Görüntülenme Rengi:</b>
                                    </div>
                                    <div class="col-1 m-auto">
                                      <input type="color" class="form-control" id="display_color"
                                        name="display_color" value="{{ old('display_color')}}">
                                    </div>
                                    <div class="col-6 m-auto">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                
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