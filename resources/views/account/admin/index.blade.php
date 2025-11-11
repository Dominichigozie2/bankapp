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
                                <h3 class="card-text text-white">$125,000</h3>
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
                                <h3 class="card-text">342</h3>
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
                                <h3 class="card-text">$78,200</h3>
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
                                <h3 class="card-text">$54,300</h3>
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
                                <h3 class="card-text">$65,000</h3>
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
                                <h3 class="card-text">$60,500</h3>
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
                                <h3 class="card-text">128</h3>
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
                                <h3 class="card-text">47</h3>
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
                                        <option value="user1@example.com">user1@example.com</option>
                                        <option value="user2@example.com">user2@example.com</option>
                                        <option value="user3@example.com">user3@example.com</option>
                                        <!-- Add more demo users -->
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

                                <button type="submit" class="btn btn-success w-100">
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
                            <table class="table table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Joined</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>John Doe</td>
                                        <td>john@example.com</td>
                                        <td>2025-11-01</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Jane Smith</td>
                                        <td>jane@example.com</td>
                                        <td>2025-11-02</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Michael Lee</td>
                                        <td>michael@example.com</td>
                                        <td>2025-11-03</td>
                                    </tr>
                                    <!-- More demo rows -->
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
                const to = document.getElementById('userEmail').value;
                const subject = document.getElementById('emailSubject').value;
                const body = document.getElementById('emailBody').value;

                // Demo action
                alert(`Email sent to ${to}\nSubject: ${subject}\nMessage: ${body}`);
                this.reset();
            });
        </script>
        @endsection