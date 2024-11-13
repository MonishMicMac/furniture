@include('header')

<div class="container my-4">
    <h1 class="text-center mb-4">Categories</h1>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title">Add a New Category</h5>
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="category_name" class="form-label">Category Name</label>
                    <input type="text" id="category_name" name="name" class="form-control" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Add Category</button>
            </form>
        </div>
    </div>

    <h2 class="mb-4">Category List</h2>
    
    <table id="usersTable"  class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Category Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $sno=0;
            ?>

            @foreach($categories as $category)

            <?php

                $sno++;
                ?>
                <tr>
                    <td>{{ $sno}}</td>
                    <td>{{ $category->name }}</td>
                    <td>
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline;" id="delete-form-{{ $category->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $category->id }})">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@include('footer')

<script>
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
                // Submit the form if the user confirms
                document.getElementById('delete-form-' + productId).submit();
            }
        });
    }
</script>
