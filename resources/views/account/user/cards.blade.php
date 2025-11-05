@extends('account.user.layout.app')

@section('content')
<style>
.credit-card {
  width: 100%;
  max-width: 400px;
  height: 230px;
  perspective: 1000px;
  margin: auto;
  position: relative;
}

.card-inner {
  position: relative;
  width: 100%;
  height: 100%;
  text-align: center;
  transition: transform 0.8s;
  transform-style: preserve-3d;
}

.credit-card:hover .card-inner {
  transform: rotateY(180deg);
}

.card-front, .card-back {
  position: absolute;
  width: 100%;
  height: 100%;
  border-radius: 15px;
  backface-visibility: hidden;
  color: #fff;
  box-shadow: 0 8px 20px rgba(0,0,0,0.3);
  padding: 20px;
  font-family: 'Poppins', sans-serif;
}

.card-front {
  background: linear-gradient(135deg, #667eea, #764ba2);
}

.card-back {
  background: linear-gradient(135deg, #764ba2, #667eea);
  transform: rotateY(180deg);
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.card-logo {
  height: 40px;
}

.card-chip {
  width: 50px;
  margin-top: 10px;
}

.card-type {
  font-size: 1rem;
  text-transform: uppercase;
  font-weight: 600;
}

.card-number {
  font-size: 1.3rem;
  letter-spacing: 3px;
  margin-top: 20px;
}

.card-name {
  position: absolute;
  bottom: 20px;
  left: 20px;
  font-weight: 600;
  font-size: 0.9rem;
}

.copy-btn, .deactivate-btn {
  margin-top: 15px;
}
</style>

<div class="page-content mt-5">
  <div class="container text-center">

    {{-- 🪪 If no card --}}
    @if(!$card)
      <div class="card shadow-sm">
        <div class="card-body">
          <h5>Request New Card</h5>
          <form id="requestCardForm">
            @csrf
            <div class="mb-3 text-start">
              <label>Account Type</label>
              <select class="form-select" name="account_type" required>
                <option value="">Choose...</option>
                <option value="savings">Savings</option>
                <option value="current">Current</option>
                <option value="business">Business</option>
              </select>
            </div>
            <div class="mb-3 text-start">
              <label>Account PIN</label>
              <input type="password" name="pin" maxlength="6" class="form-control" required>
            </div>
            <button class="btn btn-primary w-100">Request Card</button>
          </form>
        </div>
      </div>

    @else
      {{-- 🕒 Pending --}}
      @if($card->card_status == 2)
        <div class="credit-card mt-3">
          <div class="card-front d-flex flex-column justify-content-center align-items-center">
            <h5>**** **** **** ****</h5>
            <p>Awaiting admin approval...</p>
          </div>
        </div>
        <div class="alert alert-info mt-3">
          You have already requested a card. Please wait for admin approval.
        </div>

      {{-- 🟡 On Hold --}}
      @elseif($card->card_status == 3)
        <div class="credit-card mt-3">
          <div class="card-front d-flex flex-column justify-content-center align-items-center">
            <h5>**** **** **** ****</h5>
            <p>Your card request is currently on hold. Please contact support.</p>
          </div>
        </div>
        <div class="alert alert-warning mt-3">
          Your card is currently on hold. Kindly contact customer service for assistance.
        </div>

      {{-- ✅ Approved --}}
      @elseif($card->card_status == 1)
        <div class="credit-card mt-3">
          <div class="card-inner">
            {{-- Front --}}
            <div class="card-front">
              <div class="card-header">
                <img src="/assets/images/logo-sm.svg" alt="Logo" class="card-logo">
                <div class="card-type text-end">
                  <div>Debit Card</div>
                  <img src="{{ asset('assets/images/visa.png') }}" alt="Visa" width="60">
                </div>
              </div>

              <img src="{{ asset('assets/images/chip.png') }}" alt="Chip" class="card-chip">
              <div class="card-number">{{ chunk_split($card->card_number, 4, ' ') }}</div>
              <div class="card-name">{{ $card->card_name }}</div>
            </div>

            {{-- Back --}}
            <div class="card-back">
              <div style="background:#000;height:40px;margin-top:10px;"></div>
              <div style="margin-top:30px;text-align:right;">
                <strong>CVV: {{ $card->card_security }}</strong>
              </div>
              <div class="mt-4">
                <small>Expires: {{ $card->card_expiration }}</small><br>
              </div>
            </div>
          </div>
        </div>

        {{-- Buttons --}}
        <button class="btn btn-outline-light mt-3 copy-btn" id="copyCardBtn">Copy Card Details</button>
        <button class="btn btn-danger mt-2 deactivate-btn" id="deactivateCardBtn">Deactivate Card</button>
      @endif
    @endif
  </div>
</div>
@endsection


@section('scripts')
<script>
$('#requestCardForm').submit(function(e){
  e.preventDefault();
  const btn = $(this).find('button');
  btn.prop('disabled', true).text('Processing...');
  
  $.ajax({
    url: '{{ route("user.cards.request") }}',
    method: 'POST',
    data: $(this).serialize(),
    dataType: 'json'
  })
  .done(res=>{
    if(res.success){ iziToast.success({ title: 'Success', message: res.message }); setTimeout(()=>location.reload(), 1500); }
    else { iziToast.error({ title: 'Error', message: res.message }); }
  })
  .fail(()=>{ iziToast.error({ title: 'Error', message: 'Something went wrong.' }); })
  .always(()=>{ btn.prop('disabled', false).text('Request Card'); });
});

@if($card && $card->card_status == 1)
// Copy card details
$('#copyCardBtn').click(function(){
  const details = `
Card Number: {{ $card->card_number }}
Card Name: {{ $card->card_name }}
Expiry: {{ $card->card_expiration }}
CVV: {{ $card->card_security }}
Serial: {{ $card->serial_key }}
Internet ID: {{ $card->internet_id }}
  `.trim();

  navigator.clipboard.writeText(details).then(()=>{
    iziToast.success({ title: 'Copied', message: 'Card details copied to clipboard.' });
  });
});

// Deactivate card
$('#deactivateCardBtn').click(function(){
  if(!confirm('Are you sure you want to deactivate this card?')) return;

  const btn = $(this);
  btn.prop('disabled', true).text('Deactivating...');

  $.ajax({
    url: '{{ route("user.cards.deactivate") }}',
    method: 'POST',
    data: { _token: '{{ csrf_token() }}' },
    dataType: 'json'
  })
  .done(res=>{
    if(res.success){
      iziToast.success({ title: 'Deactivated', message: res.message });
      setTimeout(()=>location.reload(), 1500);
    } else {
      iziToast.error({ title: 'Error', message: res.message });
    }
  })
  .fail(()=>{
    iziToast.error({ title: 'Error', message: 'Something went wrong.' });
  })
  .always(()=>{ btn.prop('disabled', false).text('Deactivate Card'); });
});
@endif
</script>
@endsection
