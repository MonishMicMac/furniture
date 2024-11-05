@include('header')

<div class="container">
    <h2>Edit User</h2>
    <div id="error-message" class="alert alert-danger" style="display:none;"></div>
    <form id="editUserForm" action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
            <span class="text-danger error-text name_error"></span>
        </div>
        <div class="form-group">
            <label>Mobile Number</label>
            <input type="text" name="mobile_number" class="form-control" value="{{ $user->mobile_number }}" required>
            <span class="text-danger error-text mobile_number_error"></span>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
            <span class="text-danger error-text email_error"></span>
        </div>
        <div class="form-group">
            <label>Address</label>
            <input type="text" name="address" class="form-control" value="{{ $user->address }}" required>
            <span class="text-danger error-text address_error"></span>
        </div>
        <div class="form-group">
            <label>State</label>
            <input type="text" name="state" class="form-control" value="{{ $user->state }}" required>
            <span class="text-danger error-text state_error"></span>
        </div>
        <div class="form-group">
            <label>Pincode</label>
            <input type="text" name="pincode" class="form-control" value="{{ $user->pincode }}" required>
            <span class="text-danger error-text pincode_error"></span>
        </div>
        <button type="submit" class="btn btn-primary">Update User</button>
    </form>
</div>

@include('footer')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#editUserForm').on('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Clear previous error messages
            $('.error-text').text(''); 

            // Run client-side validation
            let hasError = false;

            // Validation rules
            function showError(inputName, message) {
                $('.' + inputName + '_error').text(message);
                hasError = true;
            }

            // Name validation
            const name = $('input[name="name"]').val().trim();
            if (name === '') showError('name', 'Name is required.');

            // Mobile Number validation
            const mobileNumber = $('input[name="mobile_number"]').val().trim();
            if (mobileNumber === '') showError('mobile_number', 'Mobile number is required.');
            else if (!/^\d{10}$/.test(mobileNumber)) showError('mobile_number', 'Enter a valid 10-digit mobile number.');

            // Email validation
            const email = $('input[name="email"]').val().trim();
            if (email === '') showError('email', 'Email is required.');
            else if (!/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(email)) showError('email', 'Enter a valid email.');

            // Address validation
            const address = $('input[name="address"]').val().trim();
            if (address === '') showError('address', 'Address is required.');

            // State validation
            const state = $('input[name="state"]').val().trim();
            if (state === '') showError('state', 'State is required.');

            // Pincode validation
            const pincode = $('input[name="pincode"]').val().trim();
            if (pincode === '') showError('pincode', 'Pincode is required.');
            else if (!/^\d{6}$/.test(pincode)) showError('pincode', 'Enter a valid 6-digit pincode.');

            // If there are no errors, proceed with AJAX call
            if (!hasError) {
                $.ajax({
                    url: $(this).attr('action'), // Use the form's action URL
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        Swal.fire({
  title: "Good job!",
  text: "You clicked the button!",
  icon: "success"
});
                        window.location.href = '{{ route('users.index') }}'; // Redirect after success
                    },
                    error: function (xhr) {
                        // Handle validation errors from the server
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            $.each(errors, function (key, value) {
                                $('.' + key + '_error').text(value[0]); // Display each error below the relevant input
                            });
                        } else {
                            $('#error-message').text('An error occurred. Please try again.').show();
                        }
                    }
                });
            }
        });
    });
</script>
