<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
    Route::get("/users", [UserController::class, 'show'])->name('users.index'); // List users
    Route::get("/users/edit/{id}", [UserController::class, 'edit'])->name('users.edit'); // Edit user
    Route::put("/users/update/{id}", [UserController::class, 'update'])->name('users.update');

    Route::delete("/users/delete/{id}", [UserController::class, 'destroy'])->name('users.destroy'); // Delete user
});

require __DIR__.'/auth.php';
