        @extends('account.user.layout.app')
        @section('content')
        <div class="page-content">

            <div class="page-content">

                <div class="container card p-4 w-60 mt-4">
                    <h4>More Informations Needed</h4>

                    <form id="kycForm" class="pt-md-5" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label>Front ID</label>
                                <input type="file" name="idfront" class="form-control" required>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label>Back ID</label>
                                <input type="file" name="idback" class="form-control" required>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label>ID Number</label>
                                <input type="text" name="id_no" class="form-control" required>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label>Proof of Address</label>
                                <input type="file" name="addressproof" class="form-control" required>
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
        <script>
            $('#kycForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('user.kyc.submit') }}",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.success) {
                            iziToast.success({
                                title: 'Success',
                                message: res.message
                            });
                             setTimeout(() => location.reload(), 1500); // Refresh after 1.5 seconds
                        } else {
                            iziToast.warning({
                                title: 'Notice',
                                message: res.message
                            });
                        }
                    },
                    error: function(err) {
                        iziToast.error({
                            title: 'Error',
                            message: 'Something went wrong!'
                        });
                    }
                });
            });
        </script>
        @endsection