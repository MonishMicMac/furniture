@include('header')

<div class="container mt-4">
    <h2 class="mb-4">Edit Product</h2>

    <!-- Edit Form -->
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <!-- Display Global Error Messages -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row mb-3">
            <!-- Product Name and Product Code -->
            <div class="col-md-6">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" id="name" name="name"
                    class="form-control @error('name') is-invalid @enderror" placeholder="Product Name" required
                    value="{{ old('name', $product->name) }}">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="product_code" class="form-label">Product Code</label>
                <input type="text" id="product_code" name="product_code"
                    class="form-control @error('product_code') is-invalid @enderror" placeholder="Product Code" required
                    value="{{ old('product_code', $product->product_code) }}">
                @error('product_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <!-- Price and Discount Price -->
            <div class="col-md-6">
                <label for="price" class="form-label">Price</label>
                <input type="number" id="price" name="price"
                    class="form-control @error('price') is-invalid @enderror" placeholder="Price" required
                    min="0" step="0.01" value="{{ old('price', $product->price) }}">
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="discount_price" class="form-label">Discount Price</label>
                <input type="number" id="discount_price" name="discount_price"
                    class="form-control @error('discount_price') is-invalid @enderror" placeholder="Discount Price"
                    min="0" step="0.01" value="{{ old('discount_price', $product->discount_price) }}">
                @error('discount_price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <!-- Quantity and Brand -->
           
            <div class="col-md-6">
                <label for="brand" class="form-label">Brand</label>
                <input type="text" id="brand" name="brand"
                    class="form-control @error('brand') is-invalid @enderror" placeholder="Brand Name"
                    value="{{ old('brand', $product->brand) }}">
                @error('brand')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="description" class="form-label">Product Description</label>
                <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror"
                    placeholder="Enter Product Description" required>{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>


        <!-- Product Images and Subcategory -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="product_images" class="form-label">Product Images</label>
                <input type="file" id="product_images" name="product_images[]"
                    class="form-control @error('product_images') is-invalid @enderror" accept="image/*" multiple
                    onchange="previewImages(event)">

                @error('product_images')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <!-- Preview of existing images -->
                <div id="imagePreviewContainer" class="mt-3 row row-cols-4">
                    @foreach ($productImages as $image)
                        <div class="col-3 mb-3 position-relative" data-image="{{ $image }}">
                            <img src="{{ asset('storage/' . $image) }}" alt="Product Image" class="img-thumbnail"
                                style="max-width: 100px; max-height: 100px;" data-bs-toggle="modal"
                                data-bs-target="#imageModal" onclick="openModal('{{ asset('storage/' . $image) }}')">

                            <!-- Remove Button for Existing Image -->
                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                onclick="removeImage('{{ $product->id }}', '{{ $image }}')">Remove</button>
                        </div>
                    @endforeach
                </div>

                <!-- Preview of new images -->
                <div id="newImagePreviewContainer" class="mt-3 row row-cols-4"></div>
            </div>


            <div class="col-md-6">
                <label for="sub_category_id" class="form-label">Subcategory</label>
                <select id="sub_category_id" name="sub_category_id" class="form-control" required>
                    <option value="">Select Subcategory</option>
                    @foreach ($subcategories as $subcategory)
                        <option value="{{ $subcategory->id }}" 
                            {{ $product->sub_category_id == $subcategory->id ? 'selected' : '' }}>
                            {{ $subcategory->name }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>


        <div class="row mb-3">
            <!-- Optional: Warranty Month and Minimum Order Quantity -->
            <div class="col-md-6">
                <label for="warranty_month" class="form-label">Warranty (Months)</label>
                <input type="number" id="warranty_month" name="warranty_month"
                    class="form-control @error('warranty_month') is-invalid @enderror"
                    placeholder="Warranty in Months" min="0"
                    value="{{ old('warranty_month', $product->warranty_month) }}">
                @error('warranty_month')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="min_order_qty" class="form-label">Minimum Order Quantity</label>
                <input type="number" id="min_order_qty" name="min_order_qty"
                    class="form-control @error('min_order_qty') is-invalid @enderror"
                    placeholder="Minimum Order Quantity" min="0"
                    value="{{ old('min_order_qty', $product->min_order_qty) }}">
                @error('min_order_qty')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <!-- Product Description -->
            
        </div>

        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>


    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Product Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="modalImage" src="" alt="Product Image" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>

@include('footer')

<script>
    function openModal(imageUrl) {
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imageUrl;
    }

    // Function to preview new images before submitting
    function previewImages(event) {
        const previewContainer = document.getElementById('newImagePreviewContainer');
        previewContainer.innerHTML = ''; // Clear previous previews

        const files = event.target.files;

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();

            reader.onload = function(e) {
                const imageElement = document.createElement('div');
                imageElement.classList.add('col-3', 'mb-3');
                imageElement.innerHTML = `
                    <img src="${e.target.result}" alt="Preview Image" class="img-thumbnail" style="max-width: 100px; max-height: 100px;" onclick="openModal('${e.target.result}')">
                `;
                previewContainer.appendChild(imageElement);
            }

            reader.readAsDataURL(file);
        }
    }

    // Event delegation to handle click on any image inside preview containers
    document.addEventListener('click', function(event) {
        if (event.target && event.target.tagName === 'IMG' && event.target.closest('#imagePreviewContainer')) {
            openModal(event.target.src);
        }
    });


    function removeImage(productId, imagePath) {
        // Show confirmation before removing the image
        if (confirm('Are you sure you want to remove this image?')) {
            // Send an AJAX request to remove the image
            console.log(productId); // Check if productId is valid

            $.ajax({
                url: '/products/' + productId + '/remove-image',
                type: 'DELETE',
                data: {
                    image_path: imagePath,
                    _token: '{{ csrf_token() }}' // CSRF Token
                },
                success: function(response) {
                    console.log('Image removed:', response);
                    // Remove the image from the DOM
                    $('div[data-image="' + imagePath + '"]').remove();
                },
                error: function(xhr, status, error) {
                    console.log('AJAX error:', xhr.responseText);
                }
            });
        }
    }
</script>
