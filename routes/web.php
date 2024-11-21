<?php
use App\Http\Controllers\AppBannerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LiquatationController;
use App\Http\Controllers\ProductMappingController;
use App\Http\Controllers\ProductStockController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StockChangeController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController; 
use App\Http\Controllers\PromocodeController;
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
Route::get('/productsshow', [ProductController::class, 'showProduct'])->name('products.show');

Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');

});


// catogey

Route::resource('categories', CategoryController::class);


Route::get('product_mapping/create', [ProductMappingController::class, 'create'])->name('product_mapping.create');
Route::post('product_mapping/store', [ProductMappingController::class, 'store'])->name('product_mapping.store');

Route::delete('/products/{product}/remove-image', [ProductController::class, 'removeImage']);
Route::resource('subcategories', SubcategoryController::class)->except(['create']);
Route::get('subcategories', [SubcategoryController::class, 'index'])->name('subcategories.index');

Route::get('banners/create', [AppBannerController::class, 'create'])->name('banners.create');
Route::post('/banners/store', [AppBannerController::class, 'store'])->name('banners.store');
Route::resource('banners', AppBannerController::class);



Route::get('/promocode/create', [PromocodeController::class, 'create'])->name('promocode.create');
Route::post('/promocode/store', [PromocodeController::class, 'store'])->name('promocode.store');
Route::get('/promocode/{id}/edit', [PromocodeController::class, 'edit'])->name('promocode.edit');
Route::put('/promocode/{id}', [PromocodeController::class, 'update'])->name('promocode.update');

// Route to delete a promo code
Route::delete('/promocode/{id}', [PromocodeController::class, 'destroy'])->name('promocode.destroy');


Route::get('/stock_edit', [StockChangeController::class, 'index'])->name('stock.index');

Route::get('/product_stock/edit/{id}', [StockChangeController::class, 'edit'])->name('product_stock.edit');
Route::post('/product_stock/update/{id}', [StockChangeController::class, 'update'])->name('product_stock.update');

Route::get('/add_stock', [StockChangeController::class, 'showAddStockForm'])->name('product_stock.add');
Route::post('/add_stock', [StockChangeController::class, 'handleAddStock'])->name('product_stock.handleAddStock');

// web.php
Route::get('/closing_stock', [ProductStockController::class, 'showClosingStockForm'])->name('product_stock.closing');
Route::post('/closing_stock', [ProductStockController::class, 'handleClosingStock'])->name('product_stock.closing.handle');


Route::get('/Liquatation', [LiquatationController::class, 'index'])->name('liquatation.index');
Route::post('/update-product/{id}', [LiquatationController::class, 'update'])->name('product.update');
Route::get('/product/report', [LiquatationController::class, 'showReport'])->name('liqproduct.report');

require __DIR__.'/auth.php';
