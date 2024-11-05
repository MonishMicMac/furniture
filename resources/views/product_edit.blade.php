@include('header')

<div class="container mt-4">
    <h2 class="mb-4">Edit Product</h2>

    <!-- Edit Form -->
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <!-- Product Name -->
            <div class="col-md-6">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Price -->
            <div class="col-md-6">
                <label for="price" class="form-label">Price</label>
                <input type="number" id="price" name="price" class="form-control" value="{{ old('price', $product->price) }}" required step="0.01">
                @error('price')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <!-- Description -->
            <div class="col-md-6">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" class="form-control">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Quantity -->
            <div class="col-md-6">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" id="quantity" name="quantity" class="form-control" value="{{ old('quantity', $product->quantity) }}" required min="0" placeholder="Quantity">
                @error('quantity')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <!-- Discount Price -->
            <div class="col-md-6">
                <label for="discount_price" class="form-label">Discount Price</label>
                <input type="number" id="discount_price" name="discount_price" class="form-control" value="{{ old('discount_price', $product->discount_price) }}" step="0.01" placeholder="Discount Price">
                @error('discount_price')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Brand -->
            <div class="col-md-6">
                <label for="brand" class="form-label">Brand</label>
                <input type="text" id="brand" name="brand" class="form-control" value="{{ old('brand', $product->brand) }}" placeholder="Brand Name">
                @error('brand')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <!-- Average Rating -->
            <div class="col-md-6">
                <label for="average_rating" class="form-label">Average Rating</label>
                <input type="number" id="average_rating" name="average_rating" class="form-control" value="{{ old('average_rating', $product->average_rating) }}" min="0" max="5" step="0.1" placeholder="Average Rating (0-5)">
                @error('average_rating')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Product Image -->
            <div class="col-md-6">
                <label for="product_image" class="form-label">Product Image</label>
                <input type="file" id="product_image" name="product_image" class="form-control">
                <small>Leave this blank if you don't want to change the image.</small>
                <div class="mt-2">
                    @if($product->product_image_path)
                        <img src="{{ asset('storage/' . $product->product_image_path) }}" alt="Current Image" style="max-width: 100px; max-height: 100px;">
                    @endif
                </div>
                @error('product_image')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <!-- Category -->
            <div class="col-md-6">
                <label for="category_id" class="form-label">Category</label>
                <select id="category_id" name="category_id" class="form-select" required>
                    <option value="">Select a category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $category->id == $product->category_id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>
</div>

@include('footer')
