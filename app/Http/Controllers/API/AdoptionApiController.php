<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdoptionRequest;
use Illuminate\Http\Request;

class AdoptionApiController extends Controller
{
    // List all requests (Admin)
    public function index() {
        return response()->json(AdoptionRequest::all());
    }

    // Submit an adoption request
    public function store(Request $request) {
        $data = $request->validate([
            'pet_id' => 'required',
            'user_id' => 'required',
            'notes' => 'nullable|string'
        ]);
        
        $requestData = AdoptionRequest::create($data);
        return response()->json($requestData, 201);
    }

    // Approve or reject a request
    public function update(Request $request, $id) {
        $adoption = AdoptionRequest::findOrFail($id);
        $adoption->update($request->only(['status']));
        return response()->json($adoption);
    }
}