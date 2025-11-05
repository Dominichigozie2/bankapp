@component('mail::message')
# Hello {{ $user->first_name }},

{{ $messageBody }}

---

**Loan Details**
- Amount: ${{ number_format($loan->approved_amount ?? $loan->amount, 2) }}
- Type: {{ $loan->loan_type }}
- Duration: {{ $loan->duration }}
- Status: 
    @switch($loan->status)
        @case(1) ✅ Approved @break
        @case(2) ⏳ Pending @break
        @case(3) ⏸ On Hold @break
        @case(4) ⚠️ Due @break
        @case(5) 💰 Paid Back @break
        @default ❌ Rejected
    @endswitch

---

Thanks,  
**{{ config('app.name') }}** Team
@endcomponent
