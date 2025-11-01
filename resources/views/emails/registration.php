@component('mail::message')
# Welcome, {{ $user->first_name }}!

Thank you for joining SpeedLight Bank.  
We’re excited to have you on board.

**Your account number:** {{ $user->current_account_number }}

@component('mail::button', ['url' => url('/')])
Visit Dashboard
@endcomponent

Thanks,  
**The SpeedLight Team**
@endcomponent
