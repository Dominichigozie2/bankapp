@extends('account.admin.layout.apps')
@section('content')
<div class="container mt-4">
    <h4>Manage Codes for {{ $user->first_name }} {{ $user->last_name }}</h4>

    <form method="POST" action="{{ route('admin.codes.update', $user->id) }}">
        @csrf
        <div class="mb-3">
            <label>COT Code</label>
            <input type="text" name="cot_code" class="form-control" value="{{ $userCodes->cot_code }}">
        </div>
        <div class="mb-3">
            <label>TAX Code</label>
            <input type="text" name="tax_code" class="form-control" value="{{ $userCodes->tax_code }}">
        </div>
        <div class="mb-3">
            <label>IMF Code</label>
            <input type="text" name="imf_code" class="form-control" value="{{ $userCodes->imf_code }}">
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="transfer_restricted" class="form-check-input" id="transfer_restricted" {{ $user->transfer_restricted ? 'checked' : '' }}>
            <label for="transfer_restricted" class="form-check-label">Restrict Transfers</label>
        </div>
        <button class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
