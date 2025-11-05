@component('mail::message')
# Card Request Submitted ✅

Hello **{{ $user->first_name }}**,

Your card request has been **successfully submitted**.  
Our admin team will review and approve it shortly.

### Card Details
- **Card Holder:** {{ $card->card_name }}
- **Last 4 Digits:** **** {{ $card->last4 }}
- **Expiry:** {{ $card->card_expiration }}
- **Status:** Pending Approval

We’ll notify you once your card is approved and ready for activation.

Thanks for banking with **SpeedLight Bank** 🚀  

@component('mail::button', ['url' => url('/account/cards')])
View Card Request
@endcomponent

Best regards,  
**SpeedLight Bank**
@endcomponent
