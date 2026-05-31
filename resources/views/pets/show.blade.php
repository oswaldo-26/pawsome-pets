@extends('layouts.app')

@section('title', $pet->name . ' – PAWsome Pets')

@section('content')
    <section class="section">
        <div class="section-inner">
            <div class="section-eyebrow">Pet Details</div>
            <h1 class="section-title">{{ $pet->name }}</h1>
            <p class="section-subtitle">Learn more about {{ $pet->name }} and what makes them such a great companion.</p>

            <div class="pet-detail-grid">
                <div class="pet-detail-image">
                    @if($pet->photo)
                        <img src="{{ preg_match('/^https?:\/\//', $pet->photo) ? $pet->photo : asset('images/' . $pet->photo) }}" alt="{{ $pet->name }}">
                    @else
                        <div class="pet-card-img-placeholder">
                            <span>{{ $pet->species === 'dog' ? '🐶' : ($pet->species === 'cat' ? '🐱' : '🐹') }}</span>
                            <p>No photo available</p>
                        </div>
                    @endif
                </div>

                <div class="pet-detail-sidebar">
                    <div class="pet-detail-meta">
                        <p><strong>Species:</strong> {{ ucfirst($pet->species) }}</p>
                        <p><strong>Breed:</strong> {{ ucfirst($pet->breed ?? $pet->species) }}</p>
                        <p><strong>Age Group:</strong> {{ ucfirst($pet->age_group) }}</p>
                        <p><strong>Gender:</strong> {{ ucfirst($pet->gender) }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($pet->status) }}</p>
                    </div>
                    <div class="pet-detail-features">
                        @if($pet->is_vaccinated)
                            <span class="pet-tag">Vaccinated</span>
                        @endif
                        @if($pet->good_with_kids)
                            <span class="pet-tag coral">Good w/ Kids</span>
                        @endif
                        @if($pet->good_with_pets)
                            <span class="pet-tag">Good w/ Pets</span>
                        @endif
                    </div>

                    <p class="pet-card-desc" style="margin-top:1rem;">{{ $pet->description }}</p>

                    @auth
                        @if(auth()->user()->role === 'adopter')
                             @if($pet->status === 'available')
                                <a href="{{ url('/adoption/' . $pet->id . '/apply') }}" class="btn-coral"
                                    style="margin-top:1.5rem; display:inline-block;">
                                    Apply to Adopt {{ $pet->name }} 🐾
                                </a>
                                    @else
                                        <button class="btn-coral" disabled style="opacity:0.5; cursor:not-allowed; margin-top:1.5rem; display:inline-block;">
                                            No Longer Available
                                        </button>
                                    @endif
                            @endif
                            @else
                                <a href="{{ route('login') }}" class="btn-coral" style="margin-top:1.5rem; display:inline-block;">
                                    Sign In to Adopt {{ $pet->name }} 🐾
                                </a>
                    @endauth

                    <a href="{{ url('/pets') }}" class="btn-outline" style="margin-top:1.5rem; display:inline-block;">Back to gallery</a>
                </div>
            </div>
        </div>
    </section>
@endsection
