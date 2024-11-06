@include('header')

<div class="container mt-4">
    <h2 class="mb-4">User List</h2>
    
    <!-- Approval Status Filter -->
    <form method="GET" action="{{ route('users.index') }}" class="mb-4">
        <label for="approval_status">Filter by Approval Status:</label>
        <select name="approval_status" id="approval_status" class="form-control w-25 d-inline-block">
            <option value="">All</option>
            <option value="0" {{ request('approval_status') == '0' ? 'selected' : '' }}>Waiting</option>
            <option value="1" {{ request('approval_status') == '1' ? 'selected' : '' }}>Approved</option>
            <option value="2" {{ request('approval_status') == '2' ? 'selected' : '' }}>Declined</option>
        </select>
        <button type="submit" class="btn btn-primary ml-2">Filter</button>
    </form>

    <div class="table-responsive">
        <table id="usersTable" class="table table-striped table-bordered">
            <thead class="thead-dark bg-amber-300">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Mobile Number</th>
                    <th>Shop Name</th>
                    <th>State</th>
                    <th>Approval Status</th>
                    <th>Actions</th>
                    <th>View</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sno=1;
                ?>
                @foreach ($users as $user)

                    <tr>

                        <td>{{ $sno }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->mobile_number }}</td>
                        <td>{{ $user->shop_name }}</td>
                        <td>{{ $user->state }}</td>
                        
                        <!-- Approval Status Column -->
                        <td>
                            @if($user->approval_status == '0')
                            <!-- Approve Button -->
                            <form action="{{ route('users.approve', $user->id) }}" method="POST" style="display:inline;" class="approve-form">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                            </form>
    
                            <!-- Decline Button -->
                            <form action="{{ route('users.decline', $user->id) }}" method="POST" style="display:inline;" class="decline-form">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Decline</button>
                            </form>
                        @elseif($user->approval_status == '1')
                            <button class="btn btn-success btn-sm" disabled>Approved</button>
                        @elseif($user->approval_status == '2')
                            <button class="btn btn-danger btn-sm" disabled>Declined</button>
                        @endif
                        </td>
                        
                        <!-- Actions Column -->
                        <td>
                            <!-- View Details Button -->
                         
        
                            <!-- Edit and Delete Buttons in the same column -->
                            <div class="mt-2">
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
        
                                <!-- Delete Button -->
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                        <td>   <button class="btn btn-info btn-sm view-details" data-user-id="{{ $user->id }}" data-toggle="modal" data-target="#userModal">View Details</button>
                            
                        </td>
                    </tr>
                    <?php
$sno++;
                    ?>
                    

                @endforeach
            </tbody>
        </table>
        
    </div>
</div>

<!-- User Details Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="userDetails">
                    <!-- Dynamic content will be inserted here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@include('footer')

<!-- SweetAlert2 Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Approve Form - SweetAlert
    document.querySelectorAll('.approve-form').forEach((form) => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to approve this user!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Approve',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Decline Form - SweetAlert
    document.querySelectorAll('.decline-form').forEach((form) => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to decline this user!',
                icon: 'error',
                showCancelButton: true,
                confirmButtonText: 'Decline',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Delete Form - SweetAlert
    document.querySelectorAll('.delete-form').forEach((form) => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to delete this user!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // View Details - Fetch user data and show in the modal
    $(document).ready(function() {
        $('.view-details').on('click', function() {
            var userId = $(this).data('user-id');
            
            // Use jQuery AJAX to fetch user details
            $.ajax({
                url: '/usersmodal/' + userId, // API endpoint
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    // Create the user details content
                    var userDetails = `
                        <strong>Name:</strong> ${data.name}<br>
                        <strong>Email:</strong> ${data.email}<br>
                        <strong>Mobile Number:</strong> ${data.mobile_number}<br>
                        <strong>Address:</strong> ${data.address}<br>
                        <strong>State:</strong> ${data.state}<br>
                        <strong>Pincode:</strong> ${data.pincode}<br>
                        <strong>GST No:</strong> ${data.gst_no}<br>
                        <strong>PAN No:</strong> ${data.pan_no}<br>
                        <strong>OTP:</strong> ${data.otp}
                    `;
                    
                    // Insert the content into the modal body
                    $('#userDetails').html(userDetails);
                    
                    // Open the modal after the content is loaded
                    var modal = new bootstrap.Modal(document.getElementById('userModal'));
                    modal.show();
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching user data:", error);
                }
            });
        });
    });
</script>
