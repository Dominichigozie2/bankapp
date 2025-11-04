@extends('account.user.layout.app')
@section('content')
<div class="container py-5">
    <h4 class="mb-4 fw-bold">📋 My Report History</h4>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Ticket Number</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Last Message</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                @php $latestMsg = $ticket->messages->first(); @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $ticket->ticket_number }}</strong></td>
                    <td>{{ ucfirst($ticket->type) }}</td>
                    <td>
                        @if($ticket->status === 'open')
                        <span class="badge bg-success">Open</span>
                        @else
                        <span class="badge bg-secondary">Closed</span>
                        @endif
                    </td>
                    <td>{{ Str::limit($latestMsg->message ?? '—', 40) }}</td>
                    <td>{{ $ticket->created_at->format('d M Y, h:i A') }}</td>
                    <td>
                        <button class="btn btn-sm btn-info view-btn" data-id="{{ $ticket->id }}">
                            <i class="bi bi-eye"></i> View
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">No reports found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title">Ticket Conversation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="conversation-area" class="p-2" style="max-height: 400px; overflow-y: auto;">
                    <p class="text-center text-muted">Loading...</p>
                </div>

                <form id="replyForm" class="mt-3 d-none">
                    <input type="hidden" name="ticket_id" id="ticket_id">
                    <div class="mb-3">
                        <label for="reply_message" class="form-label">Your Reply</label>
                        <textarea name="message" id="reply_message" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Send Reply</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function() {
        // 🔹 View Ticket Details
        $('.view-btn').click(function() {
            const id = $(this).data('id');
            $('#conversation-area').html('<p class="text-center text-muted">Loading...</p>');
            $('#replyForm').addClass('d-none');

            $.get(`/account/tickets/${id}/fetch`, function(res) {

                let html = '';
                res.messages.forEach(msg => {
                    const sender = msg.is_admin ? '<strong class="text-danger">Admin</strong>' : '<strong class="text-primary">You</strong>';
                    html += `
                        <div class="border rounded p-2 mb-2 ${msg.is_admin ? 'bg-light' : ''}">
                            ${sender}<br>
                            ${msg.message}<br>
                            <small class="text-muted">${msg.created_at}</small>
                        </div>
                    `;
                });

                $('#conversation-area').html(html || '<p class="text-center text-muted">No messages yet.</p>');
                $('#ticket_id').val(id);

                if (res.status === 'open') {
                    $('#replyForm').removeClass('d-none');
                }

                $('#viewModal').modal('show');
            }).fail(() => {
                iziToast.error({
                    title: 'Error',
                    message: 'Unable to load ticket details.',
                    position: 'topRight'
                });
            });
        });

        // 🔹 Handle Reply Submission
        $('#replyForm').submit(function(e) {
            e.preventDefault();
            const ticketId = $('#ticket_id').val();
            const formData = new FormData(this);

            $.ajax({
                url: `/account/tickets/${ticketId}/reply`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    iziToast.success({
                        title: 'Success',
                        message: res.message,
                        position: 'topRight'
                    });
                    $('#reply_message').val('');
                    $('#viewModal').modal('hide');
                    setTimeout(() => location.reload(), 1000);
                },
                error: function(err) {
                    iziToast.error({
                        title: 'Error',
                        message: err.responseJSON?.message || 'Something went wrong.',
                        position: 'topRight'
                    });
                }
            });
        });
    });
</script>
@endsection