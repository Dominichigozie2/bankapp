@extends('account.admin.layout.apps')
@section('content')
<div class="container py-5">
    <h4 class="fw-bold mb-4">🎟 All Support Tickets</h4>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Ticket Number</th>
                    <th>User</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $ticket->ticket_number }}</strong></td>
                    <td>{{ $ticket->user->first_name }} {{ $ticket->user->last_name }}</td>
                    <td>{{ ucfirst($ticket->type) }}</td>
                    <td>
                        @if($ticket->status === 'open')
                        <span class="badge bg-success">Open</span>
                        @else
                        <span class="badge bg-danger">Closed</span>
                        @endif
                    </td>
                    <td>{{ $ticket->created_at->format('d M Y, h:i A') }}</td>
                    <td>
                        <button class="btn btn-sm btn-info view-btn" data-id="{{ $ticket->id }}">View</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted">No tickets yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title">Ticket Conversation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="ticketMessages" class="p-3 border rounded bg-light mb-3" style="height: 300px; overflow-y:auto;"></div>

                <form id="adminReplyForm" class="d-none">
                    <div class="mb-3">
                        <textarea name="message" class="form-control" rows="3" placeholder="Type your reply..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Send Reply</button>
                </form>

                <button id="closeTicketBtn" class="btn btn-danger w-100 mt-2 d-none">Close Ticket</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$.ajaxSetup({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
});

let currentTicketId = null;

$(function() {
    $('.view-btn').click(function() {
        const id = $(this).data('id');
        currentTicketId = id;

        $.get(`/admin/tickets/${id}/fetch`, function(res) {
            const msgBox = $('#ticketMessages');
            msgBox.empty();

            res.messages.forEach(msg => {
                const bubble = `<div class="p-2 mb-2 rounded ${msg.is_admin ? 'bg-primary text-white text-end' : 'bg-white border'}">
                    <div>${msg.message}</div>
                    <small class="text-muted">${msg.created_at}</small>
                </div>`;
                msgBox.append(bubble);
            });

            if (res.ticket.status === 'open') {
                $('#adminReplyForm').removeClass('d-none');
                $('#closeTicketBtn').removeClass('d-none');
            } else {
                $('#adminReplyForm').addClass('d-none');
                $('#closeTicketBtn').addClass('d-none');
            }

            $('#viewModal').modal('show');
        });
    });

    $('#adminReplyForm').submit(function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        $.ajax({
            url: `/admin/tickets/${currentTicketId}/reply`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success(res) {
                iziToast.success({title: 'Success', message: res.message, position: 'topRight'});
                $('#viewModal').modal('hide');
                setTimeout(() => location.reload(), 1000);
            },
            error(err) {
                iziToast.error({title: 'Error', message: err.responseJSON?.message || 'Something went wrong', position: 'topRight'});
            }
        });
    });

    $('#closeTicketBtn').click(function() {
        $.post(`/admin/tickets/${currentTicketId}/close`, {}, function(res) {
            iziToast.success({title: 'Closed', message: res.message, position: 'topRight'});
            $('#viewModal').modal('hide');
            setTimeout(() => location.reload(), 1000);
        });
    });
});
</script>
@endsection
