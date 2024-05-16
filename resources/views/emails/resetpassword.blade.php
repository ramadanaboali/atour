@component('mail::message')
Dear {{$email}},
It's your verfication code send by our system
<br>
<h2 style="text-align: center">{{ $code }}</h2>
<br>
Thanks,<br>
@endcomponent
