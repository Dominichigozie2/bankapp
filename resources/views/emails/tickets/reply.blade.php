@component('mail::message')
# New reply on {{ $ticket->ticket_number }}

From: {{ ucfirst($messageModel->sender_type) }}

@component('mail::panel')
{{ $messageModel->message }}
@endcomponent

View the ticket in your account to continue the conversation.

Thanks,<br>
{{ config('app.name') }} Support
@endcomponent
