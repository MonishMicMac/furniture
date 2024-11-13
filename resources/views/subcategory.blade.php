<!-- resources/views/subcategories/index.blade.php -->

@include("header")

<div class="container">
    <h2 class="text-center ">Create Subcategory</h2>
    <!-- Subcategory creation form -->
    <form action="{{ route('subcategories.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Subcategory Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select class="form-control" id="category_id" name="category_id" required>
                <option value="">Select Category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <h3 class="mt-4">Subcategories List</h3>
    <!-- List of subcategories -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Subcategory Name</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subcategories as $subcategory)
                <tr>
                    <td>{{ $subcategory->id }}</td>
                    <td>{{ $subcategory->name }}</td>
                    <td>{{ $subcategory->category->name }}</td>
                    <td>
                        <a href="{{ route('subcategories.edit', $subcategory->id) }}" class="btn btn-warning btn-sm">Edit</a>

                        <form action="{{ route('subcategories.destroy', $subcategory->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@include("footer")
