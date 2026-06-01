<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdoptionController extends Controller
{
    /**
     * Add create() method — fetches pet and returns the adoption form view
     */
    public function create($id)
    {
        $pet = Pet::findOrFail($id);
        return view('adoption.create', compact('pet'));
    }

    /**
     * Add store() method — validates form input, inserts to adoption_requests table
     * After storing, redirect to /dashboard with a success flash message
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'occupation' => ['nullable', 'string', 'max:100'],
            'home_type'  => ['nullable', 'string'],
            'reason'     => ['nullable', 'string', 'max:1000'],
            'experience' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::table('adoption_requests')->insert([
            'user_id'        => auth()->id(),
            'pet_id'         => $id,
            'status'         => 'pending',
            'occupation'     => $request->occupation,
            'home_type'      => $request->home_type,
            'has_yard'       => $request->boolean('has_yard'),
            'has_other_pets' => $request->boolean('has_other_pets'),
            'has_children'   => $request->boolean('has_children'),
            'reason'         => $request->reason,
            'experience'     => $request->experience,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        session()->flash('success', 'Your adoption application has been submitted! We will be in touch soon.');
        return redirect('/dashboard');
    }
}