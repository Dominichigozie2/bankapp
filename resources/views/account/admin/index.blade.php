        @extends('account.admin.layout.apps')
        @section('content')


        <div class="container-fluid mt-4">

            <div class="row g-3">
                <!-- Row 1 -->
                <div class="col-md-3">
                    <div class="card text bg-primary rounded-4 h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="fs-7 fw-lighter text-white mb-4">Total Balance</h6>
                                <br>
                                <h3 class="card-text text-white">${{ number_format($totalBalance, 2) }}</h3>
                            </div>
                            <a href="#" class="hover:bg-opacity-0 icon-box bg-white bg-opacity-10 text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width:50px; height:50px;">

                                <i class="bi bi-currency-dollar fs-3 "></i>
                            </a>
                        </div>

                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text  rounded-4 h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="fs-7 fw-lighter mb-4">Total Users</h6>
                                <br>
                                <h3 class="card-text">{{ $totalUsers }}</h3>
                            </div>
                            <a href="#" class="hover:bg-opacity-0 icon-box bg-primary bg-opacity-10 text-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width:50px; height:50px;">

                                <i class="bi bi-people fs-3 "></i>
                            </a>
                        </div>

                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text rounded-4 h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="fs-7 fw-lighter mb-4">Total Wire Transfers</h6>
                                <br>
                                <h3 class="card-text">${{ number_format($totalWire, 2) }}</h3>
                            </div>
                            <a href="#" class="hover:bg-opacity-0 icon-box bg-primary bg-opacity-10 text-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width:50px; height:50px;">

                                <i class="bi bi-arrow-right-circle fs-3 "></i>
                            </a>
                        </div>

                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text rounded-4 h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="fs-7 fw-lighter mb-4">Total Domestic Transfers</h6>
                                <br>
                                <h3 class="card-text">${{ number_format($totalDomestic, 2) }}</h3>
                            </div>
                            <a href="#" class="hover:bg-opacity-0 icon-box bg-primary bg-opacity-10 text-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width:50px; height:50px;">

                                <i class="bi bi-house fs-3 "></i>
                            </a>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row g-3 mt-3">
                <!-- Row 2 -->
                <div class="col-md-3">
                    <div class="card text rounded-4 h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="fs-7 fw-lighter mb-4">Total Current Balance</h6>
                                <br>
                                <h3 class="card-text">${{ number_format($currentBalance, 2) }}</h3>
                            </div>
                            <a href="#" class="hover:bg-opacity-0 icon-box bg-primary bg-opacity-10 text-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width:50px; height:50px;">

                                <i class="bi bi-wallet2 fs-3 "></i>
                            </a>
                        </div>

                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text rounded-4 h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="fs-7 fw-lighter mb-4">Total Savings Balance</h6>
                                <br>
                                <h3 class="card-text">${{ number_format($savingsBalance, 2) }}</h3>
                            </div>
                            <a href="#" class="hover:bg-opacity-0 icon-box bg-primary bg-opacity-10 text-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width:50px; height:50px;">

                                <i class="bi bi-bank fs-3 "></i>
                            </a>
                        </div>

                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text rounded-4 h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="fs-7 fw-lighter mb-4">Total Cards</h6>
                                <br>
                                <h3 class="card-text">{{ $totalCards }}</h3>
                            </div>
                            <a href="#" class="hover:bg-opacity-0 icon-box bg-primary bg-opacity-10 text-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width:50px; height:50px;">

                                <i class="bi bi-credit-card fs-3 "></i>
                            </a>
                        </div>

                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text rounded-4 h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="fs-7 fw-lighter mb-4">Total Tickets</h6>
                                <br>
                                <h3 class="card-text">{{ $totalTickets }}</h3>
                            </div>
                            <a href="#" class="hover:bg-opacity-0 icon-box bg-primary bg-opacity-10 text-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width:50px; height:50px;">

                                <i class="bi bi-ticket-perforated fs-3 "></i>
                            </a>
                        </div>

                    </div>
                </div>
            </div>



            <!-- New row: Email + Recent Users -->
            <div class="row g-3 mt-4">
                <!-- Quick Email Form -->
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Quick Email</h5>
                        </div>
                        <div class="card-body">
                            <form id="quickEmailForm">
                                <div class="mb-3">
                                    <label for="userEmail" class="form-label">To</label>
                                    <select class="form-select" id="userEmail" required>
                                        <option value="" selected disabled>Select User</option>
                                        @foreach($users as $user)
                                        <option value="{{ $user->email }}">{{ $user->name }} ({{ $user->email }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="emailSubject" class="form-label">Subject</label>
                                    <input type="text" class="form-control" id="emailSubject" placeholder="Email Subject" required>
                                </div>

                                <div class="mb-3">
                                    <label for="emailBody" class="form-label">Message</label>
                                    <textarea class="form-control" id="emailBody" rows="5" placeholder="Type your message..." required></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-envelope-fill me-1"></i> Send
                                </button>
                            </form>

                        </div>
                    </div>
                </div>

                <!-- Recent Users Section -->
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0">Recent Users</h5>
                        </div>
                        <div class="card-body p-0">
                            <table class="table mb-0">
                                <thead class="table-light">
                                    <tr>

                                        <th></th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Joined</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentUsers as $user)
                                    <tr class="align-middle">

                                        <td>
                                            <a href="#" class="hover:bg-opacity-0 icon-box bg-primary bg-opacity-10 text-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width:30px; height:30px;">

                                                <i class="bi bi-person fs-7 "></i>
                                            </a>
                                        </td>
                                        <td>{{ $user->first_name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                    </tr>
                                    @endforeach <!-- More demo rows -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        @endsection
        @section('scripts')
        <script>
            document.getElementById('quickEmailForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const form = this;
                const data = {
                    to: document.getElementById('userEmail').value,
                    subject: document.getElementById('emailSubject').value,
                    body: document.getElementById('emailBody').value,
                    _token: '{{ csrf_token() }}'
                };

                $.post("{{ route('admin.sendEmail') }}", data, function(res) {
                    if (res.success) {
                        iziToast.success({
                            title: 'Success',
                            message: res.message
                        });
                        form.reset();
                    } else {
                        iziToast.error({
                            title: 'Error',
                            message: res.message
                        });
                    }
                });
            });
        </script>
        @endsection