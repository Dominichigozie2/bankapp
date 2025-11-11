@extends('account.user.layout.app')

@section('content')
<div class="container">
    <h2>Activities</h2>

    {{-- Filter Form --}}
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="date" name="from" class="form-control" value="{{ $dateFrom }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="to" class="form-control" value="{{ $dateTo }}">
        </div>
        <div class="col-md-3">
            <select name="type" class="form-select">
                <option value="">All Types</option>
                <option value="transfer" @selected($typeFilter=='transfer')>Transfer</option>
                <option value="deposit" @selected($typeFilter=='deposit')>Deposit</option>
                <option value="loan" @selected($typeFilter=='loan')>Loan</option>
                <option value="profile" @selected($typeFilter=='profile')>Profile</option>
                {{-- Add other types as needed --}}
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary" type="submit">Filter</button>
        </div>
    </form>

    {{-- Activities Table --}}
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Time</th>
                <th>Type</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @forelse($activities as $act)
            <tr>
                <td><i class="fa fa-calendar"></i> {{ \Carbon\Carbon::parse($act->created_at)->format('d M Y, H:i') }}</td>
                <td>{{ ucfirst($act->type) }}</td>
                <td>{{ $act->description }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">No activities found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div>
        {{ $activities->links() }}
    </div>
</div>
@endsection

@section('scripts')
<style>
/* Adjust pagination arrows */
.page-item .page-link {
    font-size: 14px;
    padding: 5px 10px;
}
</style>
@endsection
