@component('mail::message')
# 🎫 New Support Ticket Created

Hello **{{ $user->first_name }}**,  
Your support ticket has been successfully created.

---

### 🧾 Ticket Details
- **Ticket Number:** {{ $ticket->ticket_number }}
- **Type:** {{ $ticket->type }}
- **Status:** {{ ucfirst($ticket->status) }}
- **Created At:** {{ $ticket->created_at->format('d M Y, h:i A') }}

---

@component('mail::panel')
We’ve received your ticket and our support team will get back to you shortly.  
You can check the status of your ticket anytime in your account dashboard.
@endcomponent

Thanks for reaching out!  
**{{ config('app.name') }} Support Team**

@component('mail::button', ['url' => url('/account/tickets')])
View My Ticket
@endcomponent

@endcomponent
