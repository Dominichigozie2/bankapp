@extends('account.user.layout.app')

@section('content')
<div class="container mt-4">
  <div class="row">
    <div class="col">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="mb-3">Request Loan</h5>
          <form id="loanRequestForm">
            @csrf
            <div class="mb-3">
              <label>Amount</label>
              <input type="number" name="amount" class="form-control" required min="1" step="0.01">
            </div>

            <div class="mb-3">
              <label>Loan Type</label>
              <select name="loan_type" class="form-select" required>
                <option value="">Choose</option>
                <option value="business">Business Loan</option>
                <option value="individual">Individual Loan</option>
                <option value="student">Student Loan</option>
              </select>
            </div>

            <div class="mb-3">
              <label>Duration</label>
              <select name="duration" class="form-select" required>
                <option value="">Choose</option>
                <option value="1 week">1 Week</option>
                <option value="2 weeks">2 Weeks</option>
                <option value="1 month">1 Month</option>
                <option value="3 months">3 Months</option>
                <option value="1 year">1 Year</option>
              </select>
            </div>

            <div class="mb-3">
              <label>Account Code</label>
              <input type="text" name="bank_code" class="form-control" required>
            </div>

            <div class="mb-3">
              <label>Details</label>
              <textarea name="details" class="form-control" rows="3"></textarea>
            </div>

            @if(!is_null($limit))
            <div class="alert alert-info">
              Your loan limit: <strong>{{ number_format($limit,2) }}</strong>
            </div>
            @endif

            <button type="button" class="btn btn-primary w-60" id="previewLoanBtn">Request Loan</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="confirmLoanModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Confirm Loan Request</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p><strong>Amount:</strong> <span id="confirmAmount"></span></p>
        <p><strong>Duration:</strong> <span id="confirmDuration"></span></p>
        <p><strong>Interest Rate:</strong> <span id="confirmRate"></span>%</p>
        <p><strong>Repayment Amount:</strong> <span id="confirmRepay"></span></p>
        <hr>
        <p><strong>Loan Type:</strong> <span id="confirmType"></span></p>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="submitLoanBtn" class="btn btn-success">Proceed</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
const interestRates = {
  "1 week": 5,
  "2 weeks": 8,
  "1 month": 12,
  "3 months": 20,
  "1 year": 40
};

// ✅ When user clicks "Request Loan"
$('#previewLoanBtn').on('click', function(e) {
  e.preventDefault();

  const form = $('#loanRequestForm');
  const amount = parseFloat(form.find('input[name="amount"]').val());
  const duration = form.find('select[name="duration"]').val();
  const loanType = form.find('select[name="loan_type"]').val();
  const code = form.find('input[name="bank_code"]').val();
  const rate = interestRates[duration] || 0;
  const repay = amount + (amount * rate / 100);

  if (!amount || !duration || !loanType || !code) {
    iziToast.warning({ title: 'Missing', message: 'Please fill all required fields.' });
    return;
  }

  // ✅ Step 1: Validate passcode + loan limit before showing modal
  $.ajax({
    url: '{{ route("user.validate.passcode") }}',
    method: 'POST',
    data: {
      _token: '{{ csrf_token() }}',
      bank_code: code,
      amount: amount
    },
    dataType: 'json',
    success: function (res) {
      if (res.valid) {
        // ✅ Step 2: Show confirmation modal
        $('#confirmAmount').text(amount.toFixed(2));
        $('#confirmDuration').text(duration);
        $('#confirmRate').text(rate);
        $('#confirmRepay').text(repay.toFixed(2));
        $('#confirmType').text(loanType);
        $('#confirmCode').text(code);

        const modal = new bootstrap.Modal(document.getElementById('confirmLoanModal'));
        modal.show();
      } else {
        iziToast.error({
          title: 'Invalid',
          message: res.message || 'Incorrect account code or exceeds loan limit.'
        });
      }
    },
    error: function () {
      iziToast.error({ title: 'Error', message: 'Validation failed. Try again.' });
    }
  });
});

// ✅ Final submission after confirmation
$('#submitLoanBtn').on('click', function(){
  const btn = $(this);
  btn.prop('disabled', true).text('Submitting...');

  const formData = $('#loanRequestForm').serialize() + '&repayment_amount=' + $('#confirmRepay').text();

  $.ajax({
    url: '{{ route("user.loan.request") }}',
    method: 'POST',
    data: formData,
    dataType: 'json'
  }).done(function(res){
    if(res.success){
      iziToast.success({ title:'Success', message: res.message });
      setTimeout(()=>location.reload(), 1200);
    } else {
      iziToast.error({ title:'Error', message: res.message });
    }
  }).fail(function(xhr){
    const msg = xhr.responseJSON?.message || 'Error occurred';
    iziToast.error({ title:'Error', message: msg });
  }).always(function(){
    btn.prop('disabled', false).text('Proceed');
    $('#confirmLoanModal').modal('hide');
  });
});
</script>
@endsection
