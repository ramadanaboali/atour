@component('mail::message')
# Hello
New Order with code
<br>
<h2 style="text-align: center">{{ $code }}</h2>
<br>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
