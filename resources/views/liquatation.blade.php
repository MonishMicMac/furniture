    @include('header')

    <div class="container my-4">
        <h1 class="text-center mb-4">Liquatation Products</h1>
        <table id="usersTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Serial Number</th> <!-- Add Serial Number column -->
                    <th>Product Name</th>
                    <th>Product Code</th>
                    <th>Product Price</th>
                    <th>Discount Price</th>
                    <th>Current Stock</th> 
                    <th>Liquidation Status</th>
                    <th>Liquidation Price</th>
                    <th>Actions</th> <!-- For the edit button -->
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $index => $product) <!-- Use $index for serial number -->
                    <tr>
                        <td>{{ $index + 1 }}</td> <!-- Display serial number -->
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->product_code ?? 'N/A' }}</td>
                        <td>{{ $product->price ?? 'N/A' }}</td>
                        <td>{{ $product->discount_price ?? 'N/A' }}</td>
                        <td>{{ $product->latest_current_stock ?? 'N/A' }}</td>
                        <td>
                            <!-- Make liquidation status editable -->
                            <select class="form-control liquidation-status" 
                                    data-product-id="{{ $product->id }}">
                                <option value="0" {{ $product->liquidation_status == 0 ? 'selected' : '' }}>Normal</option>
                                <option value="1" {{ $product->liquidation_status == 1 ? 'selected' : '' }}>Liquidation Stock</option>
                            </select>
                        </td>
                        <td>
                            <!-- Make liquidation price editable -->
                            <input type="number" class="form-control liquidation-price" 
                                value="{{ $product->liquatation_price }}" 
                                data-product-id="{{ $product->id }}">
                        </td>
                        <td>
                            <!-- Action button to save changes -->
                            <button class="btn btn-primary btn-sm save-btn" 
                                    data-product-id="{{ $product->id }}">Save</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
    @include('footer')
    <script>
        // Handle click on the Save button
        $(document).on('click', '.save-btn', function() {
            // Get product id
            var productId = $(this).data('product-id');
            
            // Get the updated values for liquidation_status
            var updatedLiquidationStatus = $(this).closest('tr').find('.liquidation-status').val();
            
            // Temporarily enable the liquidation-price input before sending the AJAX request
            var liquidationPriceInput = $(this).closest('tr').find('.liquidation-price');
            
            // Enable the liquidation price field if it's disabled
            if (liquidationPriceInput.prop('disabled')) {
                liquidationPriceInput.prop('disabled', false);
            }
            
            // Get the updated liquidation price value
            var updatedLiquidationPrice = parseFloat(liquidationPriceInput.val());
            
            // Get the discount price for comparison (ensure it's available in the table or from a hidden field)
            var discountPrice = parseFloat($(this).closest('tr').find('.discount-price').text());
            
            // Check if liquidation price is valid
            if (updatedLiquidationPrice < 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Liquidation price cannot be less than 0.',
                    confirmButtonText: 'Try Again'
                });
                return;
            }
        
            if (updatedLiquidationPrice > discountPrice) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Liquidation price cannot be greater than discount price.',
                    confirmButtonText: 'Try Again'
                });
                return;
            }
        
            // Send an Ajax request to update the product
            $.ajax({
                url: '/update-product/' + productId,  // Your update route here
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',  // CSRF token for protection
                    liquidation_status: updatedLiquidationStatus,
                    liquidation_price: updatedLiquidationPrice
                },
                success: function(response) {
                    if (response.success) {
                        // Use SweetAlert2 to show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Product updated successfully!',
                            confirmButtonText: 'Ok'
                        });
                    } else {
                        // Use SweetAlert2 to show error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'There was an error updating the product.',
                            confirmButtonText: 'Try Again'
                        });
                    }
                },
                error: function() {
                    // Use SweetAlert2 to show error message for Ajax failure
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was an error processing your request.',
                        confirmButtonText: 'Try Again'
                    });
                },
                complete: function() {
                    // Re-disable the liquidation price field after the AJAX request completes
                    if (updatedLiquidationStatus == 0) {
                        liquidationPriceInput.prop('disabled', true);
                    }
                }
            });
        });
    
        // Handle change on liquidation status
        $(document).on('change', '.liquidation-status', function() {
            var liquidationPriceInput = $(this).closest('tr').find('.liquidation-price');
            var liquidationStatus = $(this).val();
    
            // Enable liquidation price if liquidation status is "Liquidation Stock"
            if (liquidationStatus == 1) {
                liquidationPriceInput.prop('disabled', false);
            } else {
                // Disable liquidation price if liquidation status is "Normal"
                liquidationPriceInput.prop('disabled', true);
            }
        });
    </script>
    
    
    
