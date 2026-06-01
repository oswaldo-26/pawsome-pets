<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    public function create()
    {
        return view('admin.pets.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'species' => ['required', 'in:dog,cat,small_pet'],
            'breed' => ['nullable', 'string', 'max:120'],
            'age_group' => ['required', 'in:baby,young,adult,senior'],
            'age_months' => ['nullable', 'integer', 'min:0', 'max:120'],
            'gender' => ['required', 'in:male,female'],
            'size' => ['required', 'in:small,medium,large'],
            'description' => ['nullable', 'string', 'max:1200'],
            'photo' => ['nullable', 'image', 'max:4096'],
            'status' => ['required', 'in:available,pending,adopted'],
        ]);

        $data['is_vaccinated'] = $request->boolean('is_vaccinated');
        $data['is_neutered'] = $request->boolean('is_neutered');
        $data['is_housetrained'] = $request->boolean('is_housetrained');
        $data['good_with_kids'] = $request->boolean('good_with_kids');
        $data['good_with_pets'] = $request->boolean('good_with_pets');

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('pets', 'public');
        }

        Pet::create($data);

        return redirect()->route('admin.dashboard')
            ->with('success', 'New pet added successfully.');
    }

    public function edit(Pet $pet)
    {
        return view('admin.pets.edit', compact('pet'));
    }

    public function update(Request $request, Pet $pet)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'species' => ['required', 'in:dog,cat,small_pet'],
            'breed' => ['nullable', 'string', 'max:120'],
            'age_group' => ['required', 'in:baby,young,adult,senior'],
            'age_months' => ['nullable', 'integer', 'min:0', 'max:120'],
            'gender' => ['required', 'in:male,female'],
            'size' => ['required', 'in:small,medium,large'],
            'description' => ['nullable', 'string', 'max:1200'],
            'photo' => ['nullable', 'image', 'max:4096'],
            'status' => ['required', 'in:available,pending,adopted'],
        ]);

        $data['is_vaccinated'] = $request->boolean('is_vaccinated');
        $data['is_neutered'] = $request->boolean('is_neutered');
        $data['is_housetrained'] = $request->boolean('is_housetrained');
        $data['good_with_kids'] = $request->boolean('good_with_kids');
        $data['good_with_pets'] = $request->boolean('good_with_pets');

        if ($request->hasFile('photo')) {
            if ($pet->photo && Storage::disk('public')->exists($pet->photo)) {
                Storage::disk('public')->delete($pet->photo);
            }

            $data['photo'] = $request->file('photo')->store('pets', 'public');
        }

        $pet->update($data);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Pet updated successfully.');
    }

    public function destroy(Pet $pet)
    {
        if ($pet->photo && Storage::disk('public')->exists($pet->photo)) {
            Storage::disk('public')->delete($pet->photo);
        }

        $pet->delete();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Pet removed successfully.');
    }
}
