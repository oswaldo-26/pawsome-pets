<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\AdoptionRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    /**
     * Add home() method for the homepage with featured pets and stats.
     */
    public function home()
    {
        // Kukuha ng 3 pinakabagong pets bilang "Featured Pets"
        $featuredPets = Pet::where('status', 'available')->latest()->take(3)->get();

        // Magka-calculate ng Stats para sa homepage cards
        $stats = [
            'total_pets' => Pet::count(),
            'available_pets' => Pet::where('status', 'available')->count(),
            'adopted_pets' => Pet::where('status', 'adopted')->count(),
            'total_users' => User::where('role', 'user')->count(),
        ];

        return view('home', compact('featuredPets', 'stats'));
    }

    /**
     * Move pet listing logic into index() method with filter support.
     */
    public function index(Request $request)
    {
        $query = Pet::query();

        // Filter Support (Species, Gender, Age Group)
        if ($request->has('species') && $request->species != '') {
            $query->where('species', $request->species);
        }
        if ($request->has('gender') && $request->gender != '') {
            $query->where('gender', $request->gender);
        }
        if ($request->has('age_group') && $request->age_group != '') {
            $query->where('age_group', $request->age_group);
        }

        // Kung admin ang naka-login, makikita lahat. Kung bisita, 'available' lang.
        if (auth()->check() && auth()->user()->role === 'admin') {
            $pets = $query->latest()->get();
            return view('admin.pets.index', compact('pets'));
        } else {
            $pets = $query->where('status', 'available')->latest()->get();
            return view('pets.index', compact('pets'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string',
            'breed' => 'nullable|string|max:255',
            'age' => 'required|integer|min:0',
            'age_group' => 'required|string',
            'gender' => 'required|string',
            'description' => 'required|string',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image_url')) {
            $path = $request->file('image_url')->store('pets', 'public');
            $validated['image_url'] = Storage::url($path);
        }

        $validated['status'] = 'available';

        Pet::create($validated);

        return redirect()->route('admin.dashboard')->with('success', 'Pet added successfully!');
    }

    /**
     * Move pet detail logic into show() method.
     */
    public function show(string $id)
    {
        $pet = Pet::findOrFail($id);
        return view('pets.show', compact('pet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pet = Pet::findOrFail($id);
        return view('admin.pets.edit', compact('pet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pet = Pet::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string',
            'breed' => 'nullable|string|max:255',
            'age' => 'required|integer|min:0',
            'age_group' => 'required|string',
            'gender' => 'required|string',
            'description' => 'required|string',
            'status' => 'required|string',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image_url')) {
            if ($pet->image_url) {
                $oldPath = str_replace('/storage/', '', $pet->image_url);
                Storage::disk('public')->delete($oldPath);
            }
            
            $path = $request->file('image_url')->store('pets', 'public');
            $validated['image_url'] = Storage::url($path);
        }

        $pet->update($validated);

        return redirect()->route('admin.dashboard')->with('success', 'Pet updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pet = Pet::findOrFail($id);

        if ($pet->image_url) {
            $oldPath = str_replace('/storage/', '', $pet->image_url);
            Storage::disk('public')->delete($oldPath);
        }

        $pet->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Pet deleted successfully!');
    }
}