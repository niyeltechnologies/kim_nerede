@php
use Carbon\Carbon;
@endphp

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
                        <button onclick="window.location.href='{{ route('student_course_list.show') }}'" type="button"
                            class="btn btn-success">
                            Kurs Saati Ekle
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(sizeof($userStudentAuthorityRequests)>0)
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
                        
                        <table id="student_details_list_table" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Öğrenci Adı</th>
                                    <th>Öğrenci Soyadı</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userStudentAuthorityRequests as $userStudentAuthorityRequest)
                                <tr
                                    onclick="">
                                    @php
                                    error_log('1');
                                    @endphp
                                    <td>{{ $userStudentAuthorityRequest->student_detail->student_name }}
                                    </td>
                                    <td>{{ $userStudentAuthorityRequest->student_detail->student_surname }}</td>
                                    <td>
                                        <a class="btn btn-primary" href="{{route('add_student_authority_approve.show', $userStudentAuthorityRequest->id)}}">Kabul Et</a>
                                        <a class="btn btn-danger">Sil</a>
                                    </td>
                                </tr>
                                @php
                                error_log('4');
                                @endphp
                                @endforeach
                            </tbody>
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="container">
        <div class="row p-3">
            <div class="col-12 p-5">
                <div class="row p-3">
                    <div class="col-12 text-center">
                        @if(sizeof($userStudentCourseSchedules) > 0)
                            @php
                            // Configure the visible time range and scale
                            $dayStartHour = 8; // 08:00
                            $dayEndHour = 21; // 21:00
                            $pixelsPerMinute = 1.8; // adjust to taste — controls block height/overall calendar height

                            $totalMinutes = ($dayEndHour - $dayStartHour) * 60;
                            $calendarHeight = $totalMinutes * $pixelsPerMinute;

                            $days = [
                                1 => 'Pazartesi',
                                2 => 'Salı',
                                3 => 'Çarşamba',
                                4 => 'Perşembe',
                                5 => 'Cuma',
                                6 => 'Cumartesi',
                                7 => 'Pazar',
                            ];

                            // Group schedules by day for easier rendering
                            $schedulesByDay = collect($userStudentCourseSchedules)->groupBy('day_of_week');
                            @endphp

                            <div class="calendar-wrapper">
                                <div class="calendar-grid">
                                    {{-- Time gutter column --}}
                                    <div class="calendar-time-col">
                                        <div class="calendar-header-cell"></div>
                                        @for ($hour = $dayStartHour; $hour < $dayEndHour; $hour++)
                                            <div class="calendar-hour-label" style="height: {{ 60 * $pixelsPerMinute }}px;">
                                                {{ sprintf('%02d:00', $hour) }}
                                            </div>
                                        @endfor
                                    </div>

                                    {{-- One column per day --}}
                                    @foreach ($days as $dayNumber => $dayName)
                                        <div class="calendar-day-col">
                                            <div class="calendar-header-cell">{{ $dayName }}</div>

                                            <div class="calendar-day-body" style="height: {{ $calendarHeight }}px;">
                                                @for ($hour = $dayStartHour; $hour < $dayEndHour; $hour++)
                                                    <div class="calendar-gridline"
                                                        style="top: {{ ($hour - $dayStartHour) * 60 * $pixelsPerMinute }}px;">
                                                    </div>
                                                @endfor

                                                @php
                                                // Build a simple array of events with computed start/end in minutes
                                                $dayEvents = collect($schedulesByDay->get($dayNumber, []))->map(function ($schedule) use ($dayStartHour) {
                                                    $start = \Carbon\Carbon::createFromFormat('H:i:s', $schedule->start_time);
                                                    $startMinutes = ($start->hour * 60 + $start->minute) - ($dayStartHour * 60);
                                                    $endMinutes = $startMinutes + $schedule->duration;

                                                    return [
                                                        'schedule' => $schedule,
                                                        'start' => $startMinutes,
                                                        'end' => $endMinutes,
                                                    ];
                                                })->sortBy('start')->values();

                                                // Group into clusters of mutually overlapping events
                                                $clusters = [];
                                                $currentCluster = [];
                                                $clusterEnd = null;

                                                foreach ($dayEvents as $event) {
                                                    if ($clusterEnd === null || $event['start'] < $clusterEnd) {
                                                        $currentCluster[] = $event;
                                                        $clusterEnd = max($clusterEnd ?? 0, $event['end']);
                                                    } else {
                                                        $clusters[] = $currentCluster;
                                                        $currentCluster = [$event];
                                                        $clusterEnd = $event['end'];
                                                    }
                                                }
                                                if (!empty($currentCluster)) {
                                                    $clusters[] = $currentCluster;
                                                }

                                                // Within each cluster, assign a column index to each event
                                                $positionedEvents = [];
                                                foreach ($clusters as $cluster) {
                                                    $columns = []; // tracks the "end" time occupied by each column
                                                    $clusterPositioned = [];

                                                    foreach ($cluster as $event) {
                                                        $placed = false;
                                                        foreach ($columns as $colIndex => $colEnd) {
                                                            if ($event['start'] >= $colEnd) {
                                                                $columns[$colIndex] = $event['end'];
                                                                $event['column'] = $colIndex;
                                                                $placed = true;
                                                                break;
                                                            }
                                                        }
                                                        if (!$placed) {
                                                            $columns[] = $event['end'];
                                                            $event['column'] = count($columns) - 1;
                                                        }
                                                        $clusterPositioned[] = $event;
                                                    }

                                                    // Now that we know the real final column count for this cluster,
                                                    // apply it to every event in the cluster
                                                    $totalColumns = count($columns);
                                                    foreach ($clusterPositioned as $event) {
                                                        $event['totalColumns'] = $totalColumns;
                                                        $positionedEvents[] = $event;
                                                    }
                                                }
                                                @endphp

                                                @foreach ($positionedEvents as $event)
                                                    @php
                                                    $schedule = $event['schedule'];
                                                    $start = \Carbon\Carbon::createFromFormat('H:i:s', $schedule->start_time);
                                                    $end = (clone $start)->addMinutes($schedule->duration);

                                                    $top = $event['start'] * $pixelsPerMinute;
                                                    $height = ($event['end'] - $event['start']) * $pixelsPerMinute;

                                                    $widthPercent = 100 / $event['totalColumns'];
                                                    $leftPercent = $event['column'] * $widthPercent;
                                                    @endphp

                                                    <div class="calendar-event"
                                                        style="background:{{ $schedule->student_course->student_detail->display_color }};top: {{ $top }}px; height: {{ $height }}px; left: {{ $leftPercent }}%; width: calc({{ $widthPercent }}% - 4px);"
                                                        title="{{ $schedule->student_course->student_detail->student_name }}">
                                                        <div class="calendar-event-time">{{ $start->format('H:i') }} - {{ $end->format('H:i') }}</div>
                                                        <div class="calendar-event-title">
                                                            {{ $schedule->student_course->student_detail->student_name }}
                                                            {{ $schedule->student_course->student_detail->student_surname }}
                                                        </div>
                                                        <div class="calendar-event-subtitle">
                                                            {{ $schedule->student_course->course->course_name }}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <h4>Sizin listenize kayıtlı kurs programı yok.</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>