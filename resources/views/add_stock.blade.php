@include('header')

<div class="container">
    <h2 class="text-center my-4">Add Product Stock</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('product_stock.handleAddStock') }}" method="POST">
        @csrf

        <!-- Searchable dropdown for selecting the product -->
        <div class="form-group">
            <label for="product_id">Select Product</label>
            <select class="form-control select2" id="product_id" name="product_id" required>
                <option value="">-- Select Product --</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->product_code }})</option>
                @endforeach
            </select>
        </div>

        <!-- Input field for quantity -->
        <div class="form-group">
            <label for="quantity">Add Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Add Stock</button>
    </form>
</div>

@include('footer')
