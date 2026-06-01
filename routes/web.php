<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AdoptionController; 
use App\Http\Controllers\AdminController; // Dinagdag para sa Phase 3.3
use App\Http\Controllers\DashboardController; // Dinagdag para sa Phase 3.4
use App\Http\Controllers\NotificationController; // Dinagdag para sa Phase 3.5
use App\Models\Pet; // 

// ── PUBLIC ROUTES ──

// 2.2 & 3.1 Connected to PetController@home
Route::get('/', [PetController::class, 'home'])->name('home');

// 2.1 & 3.1 Connected to PetController for index, show, store, update, destroy
Route::resource('pets', PetController::class)->only(['index', 'show']);

// 2.1 Update the GET /gallery route similarly
Route::get('/gallery', function () {
    return view('pets.gallery', [
        'pets' => Pet::all(), // Kukuha ng lahat ng pets mula sa database
    ]);
});

Route::get('/about',   function () { return view('about'); });
Route::get('/faq',     function () { return view('faq'); });
Route::get('/contact', function () { return view('contact'); });

Route::post('/contact', function () {
    request()->validate([
        'name'    => ['required', 'string', 'max:100'],
        'email'   => ['required', 'email', 'max:150'],
        'subject' => ['required', 'string', 'max:120'],
        'message' => ['required', 'string', 'max:1200'],
    ]);
    session()->flash('success', 'Thanks — your message has been sent. We will reply shortly.');
    return redirect('/contact');
});

Route::get('/rate', function () { return view('rate'); });

Route::post('/rate', function () {
    request()->validate([
        'rating'   => ['required', 'integer', 'min:1', 'max:5'],
        'comments' => ['nullable', 'string', 'max:800'],
    ]);
    session()->flash('success', 'Thanks for your feedback — we appreciate it!');
    return redirect('/rate');
});

// ── AUTH ROUTES ──
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');

// ── ADOPTER ROUTES (requires login) ──
Route::middleware(['auth'])->group(function () {

    // 3.4 Connected to DashboardController@index (Pinalitan base sa instruction)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 3.2 Adoption Routes connected to AdoptionController (Pinalitan base sa instruction)
    Route::get('/adoption/{id}/apply', [AdoptionController::class, 'create'])->name('adoption.create');
    Route::post('/adoption/{id}/apply', [AdoptionController::class, 'store'])->name('adoption.store');

    // 3.5 Connected to NotificationController@markAllRead (Pinalitan base sa instruction)
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');

});

// ── ADMIN ROUTES (requires login and admin role) ──
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    // 3.3 Connected to AdminController@index (Pinalitan base sa instruction)
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Admin management routes handle ng PetController
    Route::get('/pets/create', [PetController::class, 'create'])->name('admin.pets.create');
    Route::post('/pets', [PetController::class, 'store'])->name('admin.pets.store');
    Route::get('/pets/{id}/edit', [PetController::class, 'edit'])->name('admin.pets.edit');
    Route::put('/pets/{id}', [PetController::class, 'update'])->name('admin.pets.update');
    
    // 3.3 Connected to AdminController@destroy (Pinalitan base sa instruction)
    Route::delete('/pets/{id}', [AdminController::class, 'destroy'])->name('admin.pets.destroy');

    // 3.3 Connected to AdminController@approve (Pinalitan base sa instruction)
    Route::patch('/adoption/{id}/approve', [AdminController::class, 'approve'])->name('admin.approve');

    // 3.3 Connected to AdminController@reject (Pinalitan base sa instruction)
    Route::patch('/adoption/{id}/reject', [AdminController::class, 'reject'])->name('admin.reject');

});