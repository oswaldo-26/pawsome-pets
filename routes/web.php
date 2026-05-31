<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Auth\AuthController;

$pets = [
    (object)[
        'id' => 1,
        'name' => 'Buddy',
        'species' => 'dog',
        'breed' => 'Labrador Mix',
        'age_group' => 'young',
        'gender' => 'male',
        'photo' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSE2auLI4m20NvtuxSxFXxGrNAR_LkN4S4EGw&s',
        'is_vaccinated' => true,
        'good_with_kids' => true,
        'good_with_pets' => true,
        'status' => 'available',
        'description' => 'Friendly and energetic pup who loves playing fetch.',
    ],
    (object)[
        'id' => 2,
        'name' => 'Luna',
        'species' => 'cat',
        'breed' => 'Tabby',
        'age_group' => 'adult',
        'gender' => 'female',
        'photo' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSUzCEWSb_zWXqcimiSsnZwqtROHi-EHRbImg&s',
        'is_vaccinated' => true,
        'good_with_kids' => false,
        'good_with_pets' => true,
        'status' => 'available',
        'description' => 'Calm indoor kitty who loves cozy laps and quiet afternoons.',
    ],
    (object)[
        'id' => 3,
        'name' => 'Pip',
        'species' => 'small_pet',
        'breed' => 'Calico Cat',
        'age_group' => 'baby',
        'gender' => 'male',
        'photo' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSoEvO5xawGdEo2rCEJx9AteM_cnuLgKa_KuA&s',
        'is_vaccinated' => false,
        'good_with_kids' => true,
        'good_with_pets' => false,
        'status' => 'available',
        'description' => 'Tiny and curious friend who enjoys gentle handling.',
    ],
    (object)[
        'id' => 4,
        'name' => 'Milo',
        'species' => 'dog',
        'breed' => 'Beagle Mix',
        'age_group' => 'adult',
        'gender' => 'male',
        'photo' => 'https://i.redd.it/i-have-a-beagle-lab-mix-but-i-am-not-convinced-thats-all-v0-zc6t0uakvkdd1.jpg?width=3024&format=pjpg&auto=webp&s=469ecbf4c8cacdae210eebfb40dbc7f61ebe7a22',
        'is_vaccinated' => true,
        'good_with_kids' => true,
        'good_with_pets' => true,
        'status' => 'available',
        'description' => 'Loves sniffing trails and making new friends on walks.',
    ],
];

// ── PUBLIC ROUTES ──
Route::get('/', function () {
    return view('home');
});

Route::get('/pets', function () use ($pets) {
    $petsCollection = collect($pets);

    if ($species = request('species')) {
        $petsCollection = $petsCollection->where('species', $species);
    }
    if ($ageGroup = request('age_group')) {
        $petsCollection = $petsCollection->where('age_group', $ageGroup);
    }
    if ($gender = request('gender')) {
        $petsCollection = $petsCollection->where('gender', $gender);
    }

    return view('pets.index', [
        'pets' => $petsCollection->values()->all(),
    ]);
});

Route::get('/gallery', function () use ($pets) {
    return view('pets.gallery', [
        'pets' => collect($pets)->values()->all(),
    ]);
});

Route::get('/pets/{id}', function ($id) use ($pets) {
    $pet = collect($pets)->firstWhere('id', (int) $id);
    if (!$pet) abort(404);

    return view('pets.show', ['pet' => $pet]);
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
Route::post('/register',[AuthController::class, 'register']);
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');

// ── ADOPTER ROUTES (requires login) ──
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        $user = auth()->user();

        $requests = DB::table('adoption_requests')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($r) {
                $r->pet = DB::table('pets')->where('id', $r->pet_id)->first();
                return $r;
            });

        $notifications = DB::table('notifs')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $unreadNotifications = $notifications->where('is_read', false)->count();

        return view('dashboard', [
            'requests'            => $requests,
            'notifications'       => $notifications,
            'unreadNotifications' => $unreadNotifications,
        ]);
    })->name('dashboard');

    Route::get('/adoption/{id}/apply', function ($id) {
        $pets = [
            (object)['id'=>1,'name'=>'Buddy','species'=>'dog','breed'=>'Labrador Mix','age_group'=>'young','gender'=>'male','photo'=>'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSE2auLI4m20NvtuxSxFXxGrNAR_LkN4S4EGw&s','is_vaccinated'=>true,'is_neutered'=>false,'good_with_kids'=>true,'good_with_pets'=>true,'status'=>'available','description'=>'Friendly and energetic pup who loves playing fetch.'],
            (object)['id'=>2,'name'=>'Luna','species'=>'cat','breed'=>'Tabby','age_group'=>'adult','gender'=>'female','photo'=>'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSUzCEWSb_zWXqcimiSsnZwqtROHi-EHRbImg&s','is_vaccinated'=>true,'is_neutered'=>false,'good_with_kids'=>false,'good_with_pets'=>true,'status'=>'available','description'=>'Calm indoor kitty who loves cozy laps and quiet afternoons.'],
            (object)['id'=>3,'name'=>'Pip','species'=>'small_pet','breed'=>'Calico Cat','age_group'=>'baby','gender'=>'male','photo'=>'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSoEvO5xawGdEo2rCEJx9AteM_cnuLgKa_KuA&s','is_vaccinated'=>false,'is_neutered'=>false,'good_with_kids'=>true,'good_with_pets'=>false,'status'=>'available','description'=>'Tiny and curious friend who enjoys gentle handling.'],
            (object)['id'=>4,'name'=>'Milo','species'=>'dog','breed'=>'Beagle Mix','age_group'=>'adult','gender'=>'male','photo'=>'https://i.redd.it/i-have-a-beagle-lab-mix-but-i-am-not-convinced-thats-all-v0-zc6t0uakvkdd1.jpg?width=3024&format=pjpg&auto=webp&s=469ecbf4c8cacdae210eebfb40dbc7f61ebe7a22','is_vaccinated'=>true,'is_neutered'=>false,'good_with_kids'=>true,'good_with_pets'=>true,'status'=>'available','description'=>'Loves sniffing trails and making new friends on walks.'],
        ];
        $pet = collect($pets)->firstWhere('id', (int) $id);
        if (!$pet) abort(404);
        return view('adoption.create', ['pet' => $pet]);
    })->name('adoption.create');

    Route::post('/adoption/{id}/apply', function ($id) {
        request()->validate([
            'occupation' => ['nullable', 'string', 'max:100'],
            'home_type'  => ['nullable', 'string'],
            'reason'     => ['nullable', 'string', 'max:1000'],
            'experience' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::table('adoption_requests')->insert([
            'user_id'        => auth()->id(),
            'pet_id'         => $id,
            'status'         => 'pending',
            'occupation'     => request('occupation'),
            'home_type'      => request('home_type'),
            'has_yard'       => request()->boolean('has_yard'),
            'has_other_pets' => request()->boolean('has_other_pets'),
            'has_children'   => request()->boolean('has_children'),
            'reason'         => request('reason'),
            'experience'     => request('experience'),
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        session()->flash('success', 'Your adoption application has been submitted! We will be in touch soon.');
        return redirect('/dashboard');
    })->name('adoption.store');

    Route::post('/notifications/read-all', function () {
        DB::table('notifs')
            ->where('user_id', auth()->id())
            ->update(['is_read' => true]);
        return back();
    })->name('notifications.readAll');

});

// ── ADMIN ROUTES (requires login) ──
Route::middleware(['auth'])->prefix('admin')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard', [
            'totalPets'           => DB::table('pets')->count(),
            'availablePets'       => DB::table('pets')->where('status', 'available')->count(),
            'pendingRequests'     => DB::table('adoption_requests')->where('status', 'pending')->count(),
            'adoptedPets'        => DB::table('pets')->where('status', 'adopted')->count(),
            'pendingRequestsList' => DB::table('adoption_requests')
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($r) {
                    $r->pet  = DB::table('pets')->where('id', $r->pet_id)->first();
                    $r->user = DB::table('users')->where('id', $r->user_id)->first();
                    return $r;
                }),
            'allRequests'         => DB::table('adoption_requests')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($r) {
                    $r->pet  = DB::table('pets')->where('id', $r->pet_id)->first();
                    $r->user = DB::table('users')->where('id', $r->user_id)->first();
                    return $r;
                }),
            'pets'                => DB::table('pets')->orderBy('created_at', 'desc')->get(),
            'recentNotifications' => DB::table('notifs')
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get()
                ->map(function ($n) {
                    $n->user = DB::table('users')->where('id', $n->user_id)->first();
                    return $n;
                }),
        ]);
    })->name('admin.dashboard');

    Route::patch('/adoption/{id}/approve', function ($id) {
        DB::table('adoption_requests')->where('id', $id)->update([
            'status'      => 'approved',
            'reviewed_at' => now(),
            'updated_at'  => now(),
        ]);

        $request = DB::table('adoption_requests')->where('id', $id)->first();

        DB::table('notifs')->insert([
            'user_id'             => $request->user_id,
            'adoption_request_id' => $id,
            'title'               => 'Adoption Request Approved! 🎉',
            'message'             => 'Congratulations! Your adoption request has been approved. Please visit us to complete the process.',
            'type'                => 'approved',
            'is_read'             => false,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        // Update pet status to pending
        DB::table('pets')->where('id', $request->pet_id)->update(['status' => 'pending']);

        session()->flash('success', 'Adoption request approved and applicant notified!');
        return back();
    })->name('admin.approve');

    Route::patch('/adoption/{id}/reject', function ($id) {
        DB::table('adoption_requests')->where('id', $id)->update([
            'status'      => 'rejected',
            'reviewed_at' => now(),
            'updated_at'  => now(),
        ]);

        $request = DB::table('adoption_requests')->where('id', $id)->first();

        DB::table('notifs')->insert([
            'user_id'             => $request->user_id,
            'adoption_request_id' => $id,
            'title'               => 'Adoption Request Update',
            'message'             => 'Thank you for your interest. Unfortunately your adoption request was not approved at this time. Please feel free to apply for another pet.',
            'type'                => 'rejected',
            'is_read'             => false,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        session()->flash('success', 'Adoption request rejected and applicant notified.');
        return back();
    })->name('admin.reject');

    Route::delete('/pets/{id}', function ($id) {
        DB::table('pets')->where('id', $id)->delete();
        session()->flash('success', 'Pet removed successfully.');
        return back();
    })->name('admin.pets.destroy');

});