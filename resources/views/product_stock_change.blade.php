@include('header')

<div class="container">
    <h2 class="text-center my-4">Product Stock Management</h2>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('stock.index') }}" class="mb-4">
        <div class="form-row">
            <!-- Product Dropdown -->
            <div class="form-group col-md-6">
                <label for="product_id">Select Product</label>
                <select class="form-control" id="product_id" name="product_id">
                    <option value="">-- All Products --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ $selectedProduct == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} ({{ $product->product_code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- From Date -->

            <div class="row">
                <div class="form-group col-md-3">
                    <label for="from_date">From Date</label>
                    <input type="date" class="form-control" id="from_date" name="from_date" value="{{ $fromDate }}">
                </div>
    
                <!-- To Date -->
                <div class="form-group col-md-3">
                    <label for="to_date">To Date</label>
                    <input type="date" class="form-control" id="to_date" name="to_date" value="{{ $toDate }}">
                </div>
            </div>
            

            <!-- Filter Button -->
            <div class="form-group col-md-2 align-self-end">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <!-- Stock Table -->
    <table id="usersTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Product Code</th>
                <th>Open Balance</th>
                <th>Dispatch</th>
                <th>Balance Stock</th>
                <th>Sales</th>
                <th>Total Stock</th>
                <th>Closing Stock</th>
                <th>Other Sale</th>
                <th>Canceled Stock</th>
                <th>Decline Stock</th>
                <th>Current Stock</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($filteredStock as $stock)
                <tr>
                    <td>{{ $stock->product->name ?? 'N/A' }}</td>
                    <td>{{ $stock->product_code ?? 'N/A' }}</td>
                    <td>{{ $stock->open_balance ?? 'N/A' }}</td>
                    <td>{{ $stock->dispatch ?? 'N/A' }}</td>
                    <td>{{ $stock->balance_stock ?? 'N/A' }}</td>
                    <td>{{ $stock->sales ?? 'N/A' }}</td>
                    <td>{{ $stock->total_stock ?? 'N/A' }}</td>
                    <td>{{ $stock->closing_stock ?? 'N/A' }}</td>
                    <td>{{ $stock->other_sale ?? 'N/A' }}</td>
                    <td>{{ $stock->canceled_stock ?? 'N/A' }}</td>
                    <td>{{ $stock->decline_stock ?? 'N/A' }}</td>
                    <td>{{ $stock->current_stock ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@include('footer')
