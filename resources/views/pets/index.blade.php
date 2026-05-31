@extends('layouts.app')

@section('title', 'Adopt a Pet – PAWsome Pets')

@section('content')
<section class="section">
    <div class="section-inner">
        <div class="section-eyebrow">Adopt</div>
        <h1 class="section-title">All the pets waiting for their forever home</h1>
        <p class="section-subtitle">Use the filters below to narrow the search and find the perfect match.</p>

        <div class="search-card">
            <form action="{{ url('/pets') }}" method="GET">
                <div class="search-fields">
                    <div class="search-field">
                        <label>I'm looking for</label>
                        <select name="species">
                            <option value="">Any Pet</option>
                            <option value="dog" {{ request('species') == 'dog' ? 'selected' : '' }}>🐶 Dog</option>
                            <option value="cat" {{ request('species') == 'cat' ? 'selected' : '' }}>🐱 Cat</option>
                            <option value="small_pet" {{ request('species') == 'small_pet' ? 'selected' : '' }}>🐹 Small Pet</option>
                        </select>
                    </div>
                    <div class="search-field">
                        <label>Age Group</label>
                        <select name="age_group">
                            <option value="">Any Age</option>
                            <option value="baby" {{ request('age_group') == 'baby' ? 'selected' : '' }}>Baby</option>
                            <option value="young" {{ request('age_group') == 'young' ? 'selected' : '' }}>Young</option>
                            <option value="adult" {{ request('age_group') == 'adult' ? 'selected' : '' }}>Adult</option>
                            <option value="senior" {{ request('age_group') == 'senior' ? 'selected' : '' }}>Senior</option>
                        </select>
                    </div>
                    <div class="search-field">
                        <label>Gender</label>
                        <select name="gender">
                            <option value="">Any Gender</option>
                            <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-search">Filter Pets</button>
                </div>
            </form>
        </div>

        <div class="pets-grid">
            @forelse($pets as $pet)
                <div class="pet-card">
                    <div style="position:relative;">
                        @if($pet->photo)
                            <img src="{{ preg_match('/^https?:\/\//', $pet->photo) ? $pet->photo : asset('images/' . $pet->photo) }}" alt="{{ $pet->name }}" style="width:100%;height:220px;object-fit:cover;">
                        @else
                            <div class="pet-card-img-placeholder">
                                <span>{{ $pet->species === 'dog' ? '🐶' : ($pet->species === 'cat' ? '🐱' : '🐹') }}</span>
                                <p>No photo yet</p>
                            </div>
                        @endif
                        <div class="pet-card-status">{{ ucfirst($pet->status) }}</div>
                    </div>
                    <div class="pet-card-body">
                        <h3>{{ $pet->name }}</h3>
                        <div class="pet-card-meta">{{ ucfirst($pet->breed ?? $pet->species) }} · {{ ucfirst($pet->age_group) }} · {{ ucfirst($pet->gender) }}</div>
                        <div class="pet-tags">
                            <span class="pet-tag">{{ ucfirst($pet->species) }}</span>
                            <span class="pet-tag">{{ ucfirst($pet->age_group) }}</span>
                            @if($pet->is_vaccinated)
                                <span class="pet-tag">Vaccinated</span>
                            @endif
                            @if($pet->good_with_kids)
                                <span class="pet-tag coral">Good w/ Kids</span>
                            @endif
                        </div>
                        <p class="pet-card-desc">{{ $pet->description }}</p>
                        <a href="{{ url('/pets/' . $pet->id) }}" class="pet-card-link">View details →</a>
                    </div>
                </div>
            @empty
                <div class="section-subtitle" style="margin-top: 1rem;">No pets match your filters yet. Try adjusting the species, age group, or gender.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection
