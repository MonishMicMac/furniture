@include('header')

<!-- Main container -->
<div class="container my-4">
    <h1 class="text-center mb-4">Products</h1>

    <!-- Form to add a new product -->
    <div class="card shadow-sm mb-4 bg-light">
        <div class="card-body">
            <h5 class="card-title">Add a New Product</h5>
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <!-- Product Name and Product Code -->
                    <div class="col-md-6">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Product Name" required value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="product_code" class="form-label">Product Code</label>
                        <input type="text" id="product_code" name="product_code" class="form-control @error('product_code') is-invalid @enderror" placeholder="Product Code" required value="{{ old('product_code') }}">
                        @error('product_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            
                <div class="row mb-3">
                    <!-- Price and Discount Price -->
                    <div class="col-md-6">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" id="price" name="price" class="form-control @error('price') is-invalid @enderror" placeholder="Price" required min="0" step="0.01" value="{{ old('price') }}">
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="discount_price" class="form-label">Discount Price</label>
                        <input type="number" id="discount_price" name="discount_price" class="form-control @error('discount_price') is-invalid @enderror" placeholder="Discount Price" min="0" step="0.01" value="{{ old('discount_price') }}">
                        @error('discount_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            
                <div class="row mb-3">
                    <!-- Quantity and Brand -->
                    <div class="col-md-6">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" id="quantity" name="quantity" class="form-control @error('quantity') is-invalid @enderror" placeholder="Quantity" required min="0" value="{{ old('quantity') }}">
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="brand" class="form-label">Brand</label>
                        <input type="text" id="brand" name="brand" class="form-control @error('brand') is-invalid @enderror" placeholder="Brand Name" value="{{ old('brand') }}">
                        @error('brand')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            
                
                <div class="row mb-3">
                    <!-- Product Images and Subcategory -->
                    <div class="col-md-6">
                        <label for="product_images" class="form-label">Product Images</label>
                        <input type="file" id="product_images" name="product_images[]" class="form-control @error('product_images') is-invalid @enderror" accept="image/*" multiple required>
                        @error('product_images')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <!-- Preview container -->
                        <div id="imagePreviewContainer" class="mt-3 row row-cols-4"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="subcategory_id" class="form-label">Subcategory</label>
                        <select id="sub_category_id" name="sub_category_id" class="form-select @error('sub_category_id') is-invalid @enderror" required>

                            <option value="" disabled selected>Select a subcategory</option>
                            @foreach($subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}" {{ old('subcategory_id') == $subcategory->id ? 'selected' : '' }}>{{ $subcategory->name }}</option>
                            @endforeach
                        </select>
                        @error('subcategory_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            
                <div class="row mb-3">
                    <!-- Optional: Warranty Month and Minimum Order Quantity -->
                    <div class="col-md-6">
                        <label for="warranty_month" class="form-label">Warranty (Months)</label>
                        <input type="number" id="warranty_month" name="warranty_month" class="form-control @error('warranty_month') is-invalid @enderror" placeholder="Warranty in Months" min="0" value="{{ old('warranty_month') }}">
                        @error('warranty_month')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="min_order_qty" class="form-label">Minimum Order Quantity</label>
                        <input type="number" id="min_order_qty" name="min_order_qty" class="form-control @error('min_order_qty') is-invalid @enderror" placeholder="Minimum Order Quantity" min="0" value="{{ old('min_order_qty') }}">
                        @error('min_order_qty')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            
                <div class="row mb-3">
                    <!-- Product Description -->
                    <div class="col-md-12">
                        <label for="description" class="form-label">Product Description</label>
                        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" placeholder="Enter Product Description" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                
                <button type="submit" class="btn btn-primary">Add Product</button>
            </form>
            
        </div>
    </div>
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Selected Image" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <!-- List of products -->
    {{-- <div class="container mt-4">
        <h2 class="mb-4">Product List</h2>
        
        <!-- Product Table -->
        <table id="usersTable" class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Discount Price</th>
                    <th>Quantity</th>
                    <th>Brand</th>
                    <th>Average Rating</th>
                    <th>Category</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

                <?php

                $sno=0;
                ?>
                @foreach ($products as $product)
                <?php

                $sno++;
                ?>
                    <tr>
                        <td>{{ $sno }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->description }}</td>
                        <td>{{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->discount_price ? number_format($product->discount_price, 2) : 'N/A' }}</td>
                        <td>{{ $product->quantity }}</td>
                        <td>{{ $product->brand ?? 'N/A' }}</td>
                        <td>{{ number_format($product->average_rating, 1) ?? 'N/A' }}</td>
                        <td>{{ $product->category_name }}</td>
                        <td>
                            @if($product->product_image_path)
                                <img src="{{ asset('storage/' . $product->product_image_path) }}" alt="{{ $product->name }}" width="100">
                            @else
                                No Image
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline" id="delete-form-{{ $product->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $product->id }})">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div> --}}
</div>

@include('footer')

<script>
    function confirmDelete(productId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This will delete the product!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form if the user confirms
                document.getElementById('delete-form-' + productId).submit();
            }
        });
    }
</script>
<script>
    let selectedImages = []; // Track selected images for preview
    
    document.getElementById('product_images').addEventListener('change', function(event) {
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');
        
        // Loop through newly selected files and add them to `selectedImages`
        Array.from(event.target.files).forEach(file => {
            if (file && file.type.startsWith('image/')) {
                selectedImages.push(file); // Store file for future previews
    
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewDiv = document.createElement('div');
                    previewDiv.classList.add('position-relative', 'm-1');
                    
                    // Image preview
                    const imgElement = document.createElement('img');
                    imgElement.src = e.target.result;
                    imgElement.alt = file.name;
                    imgElement.classList.add('img-thumbnail', 'preview-image'); // Styling classes
                    imgElement.style.width = '100px';
                    imgElement.style.height = '100px';
                    imgElement.style.cursor = 'pointer';
    
                    // Remove button
                    const removeButton = document.createElement('button');
                    removeButton.innerHTML = '&times;';
                    removeButton.classList.add('btn', 'btn-danger', 'btn-sm', 'position-absolute', 'top-0', 'end-0');
                    removeButton.style.zIndex = '10';
                    
                    // Remove functionality
                    removeButton.addEventListener('click', function() {
                        previewDiv.remove();
                        removeImage(file);
                    });
    
                    // Click event for modal preview
                    imgElement.addEventListener('click', function() {
                        openModal(e.target.result);
                    });
    
                    previewDiv.appendChild(imgElement);
                    previewDiv.appendChild(removeButton);
                    imagePreviewContainer.appendChild(previewDiv);
                };
                reader.readAsDataURL(file); // Convert to base64 string
            }
        });
    });
    
    // Function to remove image from selectedImages array
    function removeImage(file) {
        selectedImages = selectedImages.filter(img => img !== file);
    }
    
    // Function to open the modal and display the clicked image
    function openModal(imageSrc) {
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imageSrc;
        const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
        imageModal.show();
    }
    </script>
    