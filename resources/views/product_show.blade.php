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
              
                <th>Image</th>
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
               
                <td>
                    @if($product->image_path)
                    <img src="{{ asset('storage/images/' . $product->image_path) }}" alt="{{ $product->name }}" width="100">

                    @else
                        No Image
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
    function viewProductDetails(productId) {
        // Get product details by ID from server-side (already passed via Blade)
        const productData = @json($products);
        const product = productData.find(p => p.id === productId);

        if (product) {
            const modalContent = `
                <h4>${product.name}</h4>
                <p><strong>Description:</strong> ${product.description}</p>
                <p><strong>Price:</strong> ${product.price ? '$' + product.price.toFixed(2) : 'N/A'}</p>
                <p><strong>Discount Price:</strong> ${product.discount_price ? '$' + product.discount_price.toFixed(2) : 'N/A'}</p>
                <p><strong>Quantity:</strong> ${product.quantity}</p>
                <p><strong>Brand:</strong> ${product.brand || 'N/A'}</p>
                <p><strong>Category:</strong> ${product.category_name}</p>
                ${product.productImage && product.productImage.image_path ? `<p><strong>Image:</strong><br><img src="${asset('storage/' + product.productImage.image_path)}" alt="${product.name}" class="img-fluid"></p>` : ''}
            `;

            // Insert product details into the modal
            document.getElementById('productDetailsContent').innerHTML = modalContent;

            // Open the modal
            const productModal = new bootstrap.Modal(document.getElementById('productModal'));
            productModal.show();
        }
    }
</script>
