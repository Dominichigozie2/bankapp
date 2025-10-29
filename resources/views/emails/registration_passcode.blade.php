@component('mail::message')
# Hello {{ $name }},

Welcome to **SpeedLight**!

Your secure login passcode is:

@component('mail::panel')
# {{ $passcode }}
@endcomponent

Please keep this code safe — you'll need it to log in.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
