        @extends('account.user.layout.app')
        @section('content')
        <div class="page-content">

            <div class="page-content">

                <div class="container card p-4 w-60 mt-4">
                    <h4>More Informations Needed</h4>
                    {{-- Mobile/Crypto Deposit Form --}}
                    <form id="mobileForm" class="pt-md-5 p-md-2" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="proof">Front ID</label>
                                <input type="file" name="proof" class="form-control" required>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="proof">Back ID</label>
                                <input type="file" name="proof" class="form-control" required>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="proof">Front ID</label>
                                <input type="number" name="proof" class="form-control" required>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="proof">Proof of Address</label>
                                <input type="file" name="proof" class="form-control" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Verify KYC</button>
                    </form>
                </div>



            </div>


            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
        @endsection

        @section('scripts')
        @endsection