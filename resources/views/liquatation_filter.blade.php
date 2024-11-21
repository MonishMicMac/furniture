@include('header')

<div class="container my-4">
    <h1 class="text-center mb-4">Product Report</h1>

    <!-- Product Type Selection Form -->
    <form method="GET" action="{{ route('liqproduct.report') }}" class="mb-4">
        <div class="form-group">
            <label for="productType">Product Type</label>
            <select class="form-control" id="productType" name="product_type">
                <option value="0">Normal</option>
                <option value="1">Liquidation Stock</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary"> Report</button>
    </form>

    @if(isset($products) && $products->isNotEmpty())
        <!-- Table for displaying the product details -->
        <table id="usersTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Serial Number</th>
                    <th>Product Name</th>
                    <th>Product Code</th>
                    <th>Product Price</th>
                    <th>Discount Price</th>
                    <th>Current Stock</th>
                    <th>Liquidation Price</th>
                    <th>Liquidation Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $index => $product)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->product_code ?? 'N/A' }}</td>
                        <td>{{ $product->price ?? 'N/A' }}</td>
                        <td>{{ $product->discount_price ?? 'N/A' }}</td>
                        <td>{{ $product->latest_current_stock ?? 'N/A' }}</td>
                        <td>{{ $product->liquatation_price ?? 'N/A' }}</td>
                        <td>{{ $product->liquidation_status == 0 ? 'Normal' : 'Liquidation Stock' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @elseif(isset($products))
        <!-- If no products found -->
        <p>No products found for the selected product type.</p>
    @endif
</div>

@include('footer')
