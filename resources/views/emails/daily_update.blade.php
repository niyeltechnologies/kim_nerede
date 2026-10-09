<h1>Merhaba {{ $maildata["name"] }} ,</h1>
<p>Bugün senin ekibin programı şöyle:</p>
@php
foreach ($maildata["daily_activities"] as $currActivity) {
echo '<p><b>' . $currActivity["course_time"]. '</b> - ' . $currActivity["student_name"] . ' (' . $currActivity["course_topic"]
    . ' - ' . $currActivity["course_name"] . ')';
    }
    @endphp
<p>Bu etkinliklere katılıp katılmadığınızı işaretlemeyi unutma!</p>
<p>Görüşürüz</p>