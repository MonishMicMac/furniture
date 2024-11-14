@include('header')

<div class="container mt-4">
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
                <th>Caterogy</th>
                <th>Subcaterogy</th>
                
                <th>Images</th>
             
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->description }}</td>
                <td>{{ number_format($product->price, 2) }}</td>
                <td>{{ $product->discount_price ? number_format($product->discount_price, 2) : 'N/A' }}</td>
                <td>{{ $product->quantity }}</td>
                <td>{{ $product->brand ?? 'N/A' }}</td>
                <td>{{ number_format($product->average_rating, 1) ?? 'N/A' }}</td>
                <td>{{ $product->category_name ?? 'N/A' }}</td> <!-- Display Category Name -->
                <td>{{ $product->subcategory_name ?? 'N/A' }}</td> <!-- Display Subcategory Name -->
                <td>
                    @if($product->images)
                        @php
                            // Split the images by comma to create an array
                            $images = explode(',', $product->images);
                        @endphp
                        @foreach($images as $image)
                            <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}" width="100" style="margin: 5px;">
                        @endforeach
                    @else
                        No Images
                    @endif
                </td>
                <td>
                    <a href="javascript:void(0);" class="btn btn-info btn-sm" onclick="viewProductDetails({{ $product->id }})">View Details</a>
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
    
</div>

<!-- Modal for Product Details -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Product Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="productDetailsContent"></div>
            </div>
        </div>
    </div>
</div>

@include('footer')

<script>
    // Confirmation of deletion
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
                document.getElementById('delete-form-' + productId).submit();
            }
        });
    }

    // View product details in a modal
   // View product details in a modal
// View product details in a modal
function viewProductDetails(productId) {
    // Get product details by ID from server-side (already passed via Blade)
    const productData = @json($products);
    const product = productData.find(p => p.id === productId);

    if (product) {
        let imagesHtml = '';
        if (product.images) {
            // Split images from the database
            const images = product.images.split(',');
            imagesHtml = images.map(image => 
                `<img src="{{ asset('storage/') }}/${image}" alt="${product.name}" class="img-fluid" style="margin: 5px;">`
            ).join('');
        }

        // Ensure price is a number before calling .toFixed()
        const price = parseFloat(product.price);
        const discountPrice = product.discount_price ? parseFloat(product.discount_price) : null;

        // Building the modal content dynamically
        const modalContent = `
            <h4>${product.name}</h4>
            <p><strong>Description:</strong> ${product.description}</p>
            <p><strong>Price:</strong> ${!isNaN(price) ? '$' + price.toFixed(2) : 'N/A'}</p>
            <p><strong>Discount Price:</strong> ${discountPrice && !isNaN(discountPrice) ? '$' + discountPrice.toFixed(2) : 'N/A'}</p>
            <p><strong>Quantity:</strong> ${product.quantity}</p>
            <p><strong>Brand:</strong> ${product.brand || 'N/A'}</p>
            <p><strong>Category:</strong> ${product.category_name || 'N/A'}</p> <!-- Display Category -->
            <p><strong>Subcategory:</strong> ${product.subcategory_name || 'N/A'}</p> <!-- Display Subcategory -->
            <p><strong>Warranty:</strong> ${product.warranty_month ? product.warranty_month + ' months' : 'N/A'}</p>
            <p><strong>Min Order Quantity:</strong> ${product.min_order_qty || 'N/A'}</p>
            <p><strong>Images:</strong><br>${imagesHtml}</p>
        `;

        // Insert product details into the modal content
        document.getElementById('productDetailsContent').innerHTML = modalContent;

        // Show the modal
        const productModal = new bootstrap.Modal(document.getElementById('productModal'));
        productModal.show();
    }
}


</script>
