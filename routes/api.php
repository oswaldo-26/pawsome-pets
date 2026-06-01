<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Pet;
use App\Models\AdoptionRequest;
use App\Models\Notification;
use App\Models\User;

Route::post('/login', function () {
    $credentials = request()->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!\Illuminate\Support\Facades\Auth::attempt($credentials)) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials.',
        ], 401);
    }

    $user = \App\Models\User::where('email', request('email'))->first();
    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'success' => true,
        'token' => $token,
        'user' => $user,
    ]);
});

Route::get('/pets', function () {
    $query = Pet::query();

    if ($species = request('species'))
        $query->where('species', $species);
    if ($ageGroup = request('age_group'))
        $query->where('age_group', $ageGroup);
    if ($gender = request('gender'))
        $query->where('gender', $gender);
    if ($status = request('status'))
        $query->where('status', $status);

    return response()->json([
        'success' => true,
        'data' => $query->get(),
        'total' => $query->count(),
    ]);
});

Route::get('/pets/{id}', function ($id) {
    $pet = Pet::find($id);

    if (!$pet) {
        return response()->json([
            'success' => false,
            'message' => 'Pet not found.',
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data' => $pet,
    ]);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user(),
        ]);
    });

    Route::get('/adoption-requests', function (Request $request) {
        $requests = AdoptionRequest::with('pet')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $requests,
        ]);
    });

    Route::post('/adoption-requests', function (Request $request) {
        $data = $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'occupation' => 'nullable|string|max:100',
            'home_type' => 'nullable|string',
            'has_yard' => 'nullable|boolean',
            'has_other_pets' => 'nullable|boolean',
            'has_children' => 'nullable|boolean',
            'reason' => 'nullable|string|max:1000',
            'experience' => 'nullable|string|max:1000',
        ]);

        $existing = AdoptionRequest::where('user_id', $request->user()->id)
            ->where('pet_id', $data['pet_id'])
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You have already applied to adopt this pet.',
            ], 422);
        }

        $adoptionRequest = AdoptionRequest::create([
            'user_id' => $request->user()->id,
            'pet_id' => $data['pet_id'],
            'status' => 'pending',
            'occupation' => $data['occupation'] ?? null,
            'home_type' => $data['home_type'] ?? null,
            'has_yard' => $data['has_yard'] ?? false,
            'has_other_pets' => $data['has_other_pets'] ?? false,
            'has_children' => $data['has_children'] ?? false,
            'reason' => $data['reason'] ?? null,
            'experience' => $data['experience'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Adoption request submitted successfully.',
            'data' => $adoptionRequest,
        ], 201);
    });

    Route::put('/adoption-requests/{id}', function (Request $request, $id) {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], 403);
        }

        $data = $request->validate([
            'status' => 'required|in:approved,rejected,completed',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $adoptionRequest = AdoptionRequest::find($id);

        if (!$adoptionRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Adoption request not found.',
            ], 404);
        }

        $adoptionRequest->update([
            'status' => $data['status'],
            'admin_notes' => $data['admin_notes'] ?? null,
            'reviewed_at' => now(),
        ]);

        Notification::create([
            'user_id' => $adoptionRequest->user_id,
            'adoption_request_id' => $adoptionRequest->id,
            'title' => $data['status'] === 'approved'
                ? 'Adoption Request Approved!'
                : 'Adoption Request Update',
            'message' => $data['status'] === 'approved'
                ? 'Congratulations! Your adoption request has been approved.'
                : 'Thank you for your interest. Unfortunately your request was not approved.',
            'type' => $data['status'],
            'is_read' => false,
        ]);

        if ($data['status'] === 'approved') {
            Pet::where('id', $adoptionRequest->pet_id)->update(['status' => 'pending']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Adoption request updated successfully.',
            'data' => $adoptionRequest->fresh(),
        ]);
    });

    Route::post('/pets', function (Request $request) {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], 403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:100',
            'species' => 'required|in:dog,cat,small_pet',
            'breed' => 'nullable|string|max:100',
            'age_group' => 'required|in:baby,young,adult,senior',
            'gender' => 'required|in:male,female',
            'size' => 'nullable|in:small,medium,large',
            'description' => 'nullable|string',
            'is_vaccinated' => 'nullable|boolean',
            'is_neutered' => 'nullable|boolean',
            'good_with_kids' => 'nullable|boolean',
            'good_with_pets' => 'nullable|boolean',
            'status' => 'nullable|in:available,pending,adopted',
        ]);

        $pet = Pet::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Pet created successfully.',
            'data' => $pet,
        ], 201);
    });

    Route::put('/pets/{id}', function (Request $request, $id) {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], 403);
        }

        $pet = Pet::find($id);

        if (!$pet) {
            return response()->json([
                'success' => false,
                'message' => 'Pet not found.',
            ], 404);
        }

        $data = $request->validate([
            'name' => 'nullable|string|max:100',
            'species' => 'nullable|in:dog,cat,small_pet',
            'breed' => 'nullable|string|max:100',
            'age_group' => 'nullable|in:baby,young,adult,senior',
            'gender' => 'nullable|in:male,female',
            'size' => 'nullable|in:small,medium,large',
            'description' => 'nullable|string',
            'is_vaccinated' => 'nullable|boolean',
            'is_neutered' => 'nullable|boolean',
            'good_with_kids' => 'nullable|boolean',
            'good_with_pets' => 'nullable|boolean',
            'status' => 'nullable|in:available,pending,adopted',
        ]);

        $pet->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Pet updated successfully.',
            'data' => $pet->fresh(),
        ]);
    });

    Route::delete('/pets/{id}', function (Request $request, $id) {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], 403);
        }

        $pet = Pet::find($id);

        if (!$pet) {
            return response()->json([
                'success' => false,
                'message' => 'Pet not found.',
            ], 404);
        }

        $pet->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pet deleted successfully.',
        ]);
    });

    Route::get('/notifications', function (Request $request) {
        $notifications = Notification::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $notifications,
            'unread' => $notifications->where('is_read', false)->count(),
        ]);
    });

    Route::post('/notifications/read-all', function (Request $request) {
        Notification::where('user_id', $request->user()->id)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read.',
        ]);
    });

});