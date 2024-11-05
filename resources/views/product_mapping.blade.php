<!-- resources/views/product_mapping/create.blade.php -->

@include('header')

<div class="container mt-4">
    <h2>Product-Category Mapping</h2>

    <!-- Mapping Form -->
    <form action="{{ route('product_mapping.store') }}" method="POST" class="mb-4">
        @csrf

        <div class="row mb-3">
            <!-- Select Product -->
            <div class="col-md-6">
                <label for="product_id" class="form-label">Product</label>
                <select id="product_id" name="product_id" class="form-select" required>
                    <option value="">Select a product</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
                @error('product_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Select Category -->
            <div class="col-md-6">
                <label for="category_id" class="form-label">Category</label>
                <select id="category_id" name="category_id" class="form-select" required>
                    <option value="">Select a category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Map Category to Product</button>
    </form>

    <!-- Table for Product Mappings -->
    <h3>Existing Product-Category Mappings</h3>
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Categories</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productMappings as $productId => $mappings)
                <tr>
                    <td>{{ $mappings->first()->product->name }}</td>
                    <td>
                        {{ $mappings->pluck('category.name')->join(', ') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@include('footer')
