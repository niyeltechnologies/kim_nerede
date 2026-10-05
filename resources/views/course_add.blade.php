<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Yeni Kurs Bilgileri
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
                    <form action="{{ route('add_course.save') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row p-3">
                            <div class="col-md-6 col-12">
                                <div class="row">
                                    <div class="col-5 m-auto">
                                        <b>Kurs (Hoca) İsmi:</b>
                                    </div>
                                    <div class="col-7 m-auto">
                                        <input type="text" class="form-control" id="course_name" name="course_name"
                                            value="{{ old('course_name')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="row">
                                    <div class="col-5 m-auto">
                                        <b>Kurs Konusu:</b>
                                    </div>
                                    <div class="col-7 m-auto">
                                        <input type="text" class="form-control" id="course_topic" name="course_topic"
                                            value="{{ old('course_topic')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row p-3">
                            <div class="col-md-6 col-12">
                                <div class="row">
                                    <div class="col-5 m-auto">
                                        <b>Hoca Telefonu:</b>
                                    </div>
                                    <div class="col-7 m-auto">
                                        <input type="text" class="form-control" id="instructor_phone" name="instructor_phone"
                                            value="{{ old('instructor_phone')}}">
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