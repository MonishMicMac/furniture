@include('header')

<div class="container">
    <h2>Create New App Banner</h2>

    <!-- Display validation errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Banner Creation Form -->
    <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data" id="bannerForm">
        @csrf

        <div class="form-group">
            <label for="img_path">Banner Image</label>
            <input type="file" name="img_path" class="form-control" id="imgInput" required onchange="previewImage(event)">
        </div>

        <div class="form-group">
            <label for="type">Banner Type</label>
            <select name="type" class="form-control" required>
                <option value="dashboard">Dashboard</option>
                <option value="promo">Promo</option>
                <option value="advertisement">Advertisement</option>
            </select>
        </div>

        <!-- Image Preview Section -->
        <div id="imagePreviewContainer" class="mt-3" style="display:none;">
            <img id="imagePreview" src="" alt="Image Preview" style="width: 200px; cursor: pointer;" onclick="openModal()">
            <!-- Remove button next to preview -->
            <button type="button" id="removeBtn" class="btn btn-danger" onclick="removeImage()">Remove</button>
        </div>

        <button type="submit" class="btn btn-primary" id="submitBtn">Create Banner</button>
    </form>

    <!-- Data Table Section for Existing Banners -->
    <div class="mt-5">
        <h2>Existing Banners</h2>
        <table id="bannersTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>S.no</th>
                    <th>Image</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 0; ?>
                <!-- Loop through the banners and display them in the table -->
                @foreach($banners as $banner)
                <tr>
                    <td>{{ ++$no }}</td>
                    <td>
                        <img src="{{ asset('storage/' . $banner->img_path) }}" alt="Banner Image" style="width: 100px;">
                    </td>
                    <td>{{ ucfirst($banner->type) }}</td>
                    <td>
                        <!-- Action buttons (e.g., edit or delete) -->
                        <a href="{{ route('banners.edit', $banner->id) }}" class="btn btn-warning btn-sm">Edit</a>

                        <!-- Delete Banner Form -->
                        <form action="{{ route('banners.destroy', $banner->id) }}" method="POST" class="delete-form" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger delete-btn">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for Image Preview -->
<div class="modal" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <img id="modalImage" src="" alt="Large Preview" class="img-fluid" style="max-width: 100%; max-height: 400px;">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@include('footer')

<!-- Include DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<!-- Include SweetAlert Library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert/dist/sweetalert.min.js"></script>

<script>
    // Initialize DataTable
    $(document).ready(function() {
        $('#bannersTable').DataTable();
    });

    // Disable submit button after form submission to prevent multiple submissions
    document.getElementById('bannerForm').onsubmit = function() {
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('submitBtn').innerText = 'Submitting...';
    };

    // Preview image before uploading
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imagePreview = document.getElementById('imagePreview');
                imagePreview.src = e.target.result;
                document.getElementById('imagePreviewContainer').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    }

    // Open image in modal for a larger view
    function openModal() {
        const imagePreview = document.getElementById('imagePreview');
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imagePreview.src;

        var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
        myModal.show();
    }

    // Remove image preview and reset file input
    function removeImage() {
        document.getElementById('imgInput').value = '';
        document.getElementById('imagePreview').src = '';
        document.getElementById('imagePreviewContainer').style.display = 'none';
    }

    // SweetAlert confirmation for delete action
    $(document).on('click', '.delete-btn', function(event) {
        const form = $(this).closest('.delete-form');

        // Show SweetAlert confirmation dialog
        swal({
            title: "Are you sure?",
            text: "This banner will be deleted!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                form.submit();
            }
        });
    });
</script>
