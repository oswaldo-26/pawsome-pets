<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use Illuminate\Http\Request;

class PetApiController extends Controller
{
    // List all available pets (Requirement)
    public function index() {
        return response()->json(Pet::where('status', 'available')->get());
    }

    public function show($id) {
        return response()->json(Pet::findOrFail($id));
    }

    // Admin only (Requirement)
    public function store(Request $request) {
        $pet = Pet::create($request->all());
        return response()->json($pet, 201);
    }

    // Admin only (Requirement)
    public function update(Request $request, $id) {
        $pet = Pet::findOrFail($id);
        $pet->update($request->all());
        return response()->json($pet);
    }

    // Admin only (Requirement)
    public function destroy($id) {
        Pet::destroy($id);
        return response()->json(null, 204);
    }
}