@extends('account.user.layout.app')
@section('content')
<div class="page-content">

    <div class="page-content">

        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card shadow-sm border-0">
                        <div class="mt-4">
                            <div class="card-header">
                                <h5 class="mb-0">Loan History</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-hover align-middle">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Amount</th>
                                            <th>Type</th>
                                            <th>Duration</th>
                                            <th></th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                            

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($loans as $loan)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>${{ number_format($loan->amount, 2) }}</td>
                                            <td>{{ ucfirst($loan->loan_type) }}</td>
                                            <td>{{ $loan->duration }}</td>
                                            <td>
                                                @if($loan->status == 1 && $loan->due_date)
                                                <span id="countdown-{{ $loan->id }}"></span>
                                                <script>
                                                    (function() {
                                                        const endDate = new Date("{{ $loan->due_date }}").getTime();
                                                        const el = document.getElementById("countdown-{{ $loan->id }}");
                                                        const timer = setInterval(() => {
                                                            const now = new Date().getTime();
                                                            const distance = endDate - now;

                                                            if (distance <= 0) {
                                                                el.innerHTML = "<span class='text-danger'>Due</span>";
                                                                clearInterval(timer);
                                                                return;
                                                            }

                                                            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                                            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                                            el.innerHTML = `${days}d ${hours}h ${minutes}m`;
                                                        }, 1000);
                                                    })();
                                                </script>
                                                @else
                                                <span class="text-muted">N/A</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if($loan->status == 1)
                                                <span class="badge bg-success">Active</span>
                                                @elseif($loan->status == 2)
                                                <span class="badge bg-warning text-dark">Pending</span>
                                                @elseif($loan->status == 3)
                                                <span class="badge bg-danger">Rejected</span>
                                                @elseif($loan->status == 4)
                                                <span class="badge bg-info">Completed</span>
                                                @endif
                                            </td>
                                            <td>{{ $loan->created_at->format('d M, Y') }}</td>
                                            @if($loan->status == 1)
                                            <td>
                                                                                                        <button class="btn btn-success btn-sm repay-btn" data-id="{{ $loan->id }}">
                                                            Repay Loan
                                                        </button>
                                            </td>
                                            @endif

                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No loan records found.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- container-fluid -->
    </div>
</div>
<!-- End Page-content -->

@endsection
@section('scripts')
<script>
    // 🟢 Repay Loan
$(document).on('click', '.repay-btn', function() {
  const id = $(this).data('id');

  iziToast.question({
    timeout: false,
    close: false,
    overlay: true,
    displayMode: 'once',
    id: 'repayConfirm',
    title: 'Confirm Repayment',
    message: 'Are you sure you want to repay this loan now?',
    position: 'center',
    buttons: [
      ['<button><b>YES</b></button>', function(instance, toast) {
        instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
        $.ajax({
          url: '/account/loan/repay/' + id,
          method: 'POST',
          data: { _token: '{{ csrf_token() }}' },
          success: function(res) {
            if (res.success) {
              iziToast.success({ title: 'Success', message: res.message });
              setTimeout(() => location.reload(), 1500);
            } else {
              iziToast.error({ title: 'Error', message: res.message });
            }
          },
          error: function(xhr) {
            iziToast.error({ title: 'Error', message: xhr.responseJSON?.message || 'Something went wrong.' });
          }
        });
      }, true],
      ['<button>NO</button>', function(instance, toast) {
        instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
      }]
    ]
  });
});

</script>
@endsection