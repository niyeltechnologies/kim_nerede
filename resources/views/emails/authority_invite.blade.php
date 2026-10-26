<h1>Merhaba, </h1>
<p>{{ $maildata["name"] }} isimli katılımcıye erişmek için <a href="https://www.kimlernerde.com/register" target="_blank">sisteme kaydolsana. </a></p>

<p>Katılımcıyi kabul etmek için girmen gereken kabul kodu: {{ $maildata["approval_code"] }}</p>
<p>Görüşürüz,</p>
<p>{{ $maildata["sender"] }}</p>