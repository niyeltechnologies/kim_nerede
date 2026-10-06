<h1>Merhaba, </h1>
<p>{{ $maildata->name }} isimli öğrenciye erişmek için sisteme kaydolsana.</p>
<p>Öğrenciyi kabul etmek için girmen gereken kabul kodu: {{ $maildata->approval_code }}</p>
<p>Görüşürüz,</p>
<p>{{ $maildata->sender }}</p>