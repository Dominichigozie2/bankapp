@extends('account.user.layout.app')
@section('content')
<div class="page-content">

    <div class="page-content">

        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h4 class="card-title mb-4 text-center">Bank History</h4>

                            <a href="#" class="btn btn-primary">Download Statement</a>
                            <br>
                            <br>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>S/N</th>
                                            <th>Refrence ID</th>
                                            <th>Amount</th>
                                            <th>Type</th>
                                            <th>Payment Account</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No loan records found.</td>
                                        </tr>
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
@endsection