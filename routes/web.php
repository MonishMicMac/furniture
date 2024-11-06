<?php
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductMappingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

Route::get('/index', function () {
    return view('index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User management routes
    Route::get('/users', [UserController::class, 'show'])->name('users.index');
    Route::get("/users/edit/{id}", [UserController::class, 'edit'])->name('users.edit'); // Edit user
    Route::put("/users/update/{id}", [UserController::class, 'update'])->name('users.update');
// web.php
Route::post('/users/{id}/approve', [UserController::class, 'approve'])->name('users.approve');
Route::post('/users/{id}/decline', [UserController::class, 'decline'])->name('users.decline');
Route::get('/usersmodal/{id}', [UserController::class, 'showinmodal']);

    Route::delete("/users/delete/{id}", [UserController::class, 'destroy'])->name('users.destroy'); // Delete user




    // products 


    

Route::get('/products/show', [ProductController::class, 'index'])->name('products.index');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');

});


// catogey

Route::resource('categories', CategoryController::class);


Route::get('product_mapping/create', [ProductMappingController::class, 'create'])->name('product_mapping.create');
Route::post('product_mapping/store', [ProductMappingController::class, 'store'])->name('product_mapping.store');



Route::resource('subcategories', SubcategoryController::class)->except(['create']);
Route::get('subcategories', [SubcategoryController::class, 'index'])->name('subcategories.index');



require __DIR__.'/auth.php';
