<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Auth\AuthController;
use App\Models\Pet;
use App\Models\AdoptionRequest;
use App\Models\Notification;

Route::get('/', function () {
    return view('home', [
        'featuredPets' => Pet::where('status', 'available')->take(3)->get(),
        'stats' => [
            'available' => Pet::where('status', 'available')->count(),
            'adopted' => Pet::where('status', 'adopted')->count(),
        ],
    ]);
});

Route::get('/pets', function () {
    $query = Pet::query();

    if ($species = request('species')) {
        $query->where('species', $species);
    }

    if ($ageGroup = request('age_group')) {
        $query->where('age_group', $ageGroup);
    }

    if ($gender = request('gender')) {
        $query->where('gender', $gender);
    }

    return view('pets.index', [
        'pets' => $query->get(),
    ]);
});

Route::get('/gallery', function () {
    return view('pets.gallery', [
        'pets' => Pet::all(),
    ]);
});

Route::get('/pets/{id}', function ($id) {
    $pet = Pet::findOrFail($id);

    return view('pets.show', ['pet' => $pet]);
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/faq', function () {
    return view('faq');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::post('/contact', function () {
    request()->validate([
        'name' => ['required', 'string', 'max:100'],
        'email' => ['required', 'email', 'max:150'],
        'subject' => ['required', 'string', 'max:120'],
        'message' => ['required', 'string', 'max:1200'],
    ]);

    session()->flash('success', 'Thanks — your message has been sent. We will reply shortly.');

    return redirect('/contact');
});

Route::get('/rate', function () {
    return view('rate');
});

Route::post('/rate', function () {
    request()->validate([
        'rating' => ['required', 'integer', 'min:1', 'max:5'],
        'comments' => ['nullable', 'string', 'max:800'],
    ]);

    session()->flash('success', 'Thanks for your feedback — we appreciate it!');

    return redirect('/rate');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        $requests = AdoptionRequest::with('pet')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->get();

        $unreadNotifications = $notifications->where('is_read', false)->count();

        return view('dashboard', [
            'requests' => $requests,
            'notifications' => $notifications,
            'unreadNotifications' => $unreadNotifications,
        ]);
    })->name('dashboard');

    Route::get('/adoption/{id}/apply', function ($id) {
        $pet = Pet::findOrFail($id);

        return view('adoption.create', ['pet' => $pet]);
    })->name('adoption.create');

    Route::post('/adoption/{id}/apply', function ($id) {
        request()->validate([
            'occupation' => ['nullable', 'string', 'max:100'],
            'home_type' => ['nullable', 'string'],
            'reason' => ['nullable', 'string', 'max:1000'],
            'experience' => ['nullable', 'string', 'max:1000'],
        ]);

        AdoptionRequest::create([
            'user_id' => auth()->id(),
            'pet_id' => $id,
            'status' => 'pending',
            'occupation' => request('occupation'),
            'home_type' => request('home_type'),
            'has_yard' => request()->boolean('has_yard'),
            'has_other_pets' => request()->boolean('has_other_pets'),
            'has_children' => request()->boolean('has_children'),
            'reason' => request('reason'),
            'experience' => request('experience'),
        ]);

        session()->flash('success', 'Your adoption application has been submitted! We will be in touch soon.');

        return redirect('/dashboard');
    })->name('adoption.store');

    Route::post('/notifications/read-all', function () {
        Notification::where('user_id', auth()->id())
            ->update(['is_read' => true]);

        return back();
    })->name('notifications.readAll');
});

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard', [
            'totalPets' => Pet::count(),
            'availablePets' => Pet::where('status', 'available')->count(),
            'pendingRequests' => AdoptionRequest::where('status', 'pending')->count(),
            'adoptedPets' => Pet::where('status', 'adopted')->count(),
            'pendingRequestsList' => AdoptionRequest::with(['pet', 'user'])
                ->where('status', 'pending')
                ->latest()
                ->get(),
            'allRequests' => AdoptionRequest::with(['pet', 'user'])
                ->latest()
                ->get(),
            'pets' => Pet::latest()->get(),
            'recentNotifications' => Notification::with('user')
                ->latest()
                ->take(10)
                ->get(),
        ]);
    })->name('admin.dashboard');

    Route::patch('/adoption/{id}/approve', function ($id) {
        $adoptionRequest = AdoptionRequest::findOrFail($id);

        $adoptionRequest->update([
            'status' => 'approved',
            'reviewed_at' => now(),
        ]);

        Notification::create([
            'user_id' => $adoptionRequest->user_id,
            'adoption_request_id' => $id,
            'title' => 'Adoption Request Approved!',
            'message' => 'Congratulations! Your adoption request has been approved. Please visit us to complete the process.',
            'type' => 'approved',
            'is_read' => false,
        ]);

        Pet::where('id', $adoptionRequest->pet_id)->update(['status' => 'pending']);

        session()->flash('success', 'Adoption request approved and applicant notified!');

        return back();
    })->name('admin.approve');

    Route::patch('/adoption/{id}/reject', function ($id) {
        $adoptionRequest = AdoptionRequest::findOrFail($id);

        $adoptionRequest->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
        ]);

        Notification::create([
            'user_id' => $adoptionRequest->user_id,
            'adoption_request_id' => $id,
            'title' => 'Adoption Request Update',
            'message' => 'Thank you for your interest. Unfortunately your adoption request was not approved at this time. Please feel free to apply for another pet.',
            'type' => 'rejected',
            'is_read' => false,
        ]);

        session()->flash('success', 'Adoption request rejected and applicant notified.');

        return back();
    })->name('admin.reject');

    Route::delete('/pets/{id}', function ($id) {
        Pet::findOrFail($id)->delete();

        session()->flash('success', 'Pet removed successfully.');

        return back();
    })->name('admin.pets.destroy');
});