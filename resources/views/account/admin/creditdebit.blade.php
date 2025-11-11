@extends('account.admin.layout.apps')
@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Credit/Debit User</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('admin.creditdebit.process') }}">
                @csrf
                <div class="mb-3">
                    <label for="user_id" class="form-label">Select User</label>
                    <select class="form-select" name="user_id" id="user_id" required>
                        <option value="" selected disabled>Select User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="account_type_id" class="form-label">Funding Account</label>
                    <select class="form-select" name="account_type_id" id="account_type_id" required>
                        <option value="" selected disabled>Select Account</option>
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" name="amount" class="form-control" id="amount" placeholder="Enter amount" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" name="description" class="form-control" id="description" placeholder="Transaction description">
                </div>

                <div class="mb-3">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" name="date" class="form-control" id="date" required>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" name="action" value="create" class="btn btn-success w-50">Credit</button>
                    <button type="submit" name="action" value="debit" class="btn btn-danger w-50">Debit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
