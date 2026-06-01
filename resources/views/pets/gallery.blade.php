@extends('layouts.app')

@section('title', 'Pet Gallery – PAWsome Pets')

@section('content')
<section class="section">
    <div class="section-inner">
        <div class="section-eyebrow">Gallery</div>
        <h1 class="section-title">Shelter Pet Showcase</h1>
        <p class="section-subtitle">A photo wall of our cute pets.</p>

        <div class="gallery-grid">
            @forelse($pets as $pet)
                <a href="{{ url('/pets/' . $pet->id) }}" class="gallery-card">
                    @if($pet->photo)
                        <img src="{{ $pet->photo_url }}" alt="{{ $pet->name }}">
                    @else
                        <div class="pet-card-img-placeholder">
                            <span>{{ $pet->species === 'dog' ? '🐶' : ($pet->species === 'cat' ? '🐱' : '🐹') }}</span>
                            <p>No photo yet</p>
                        </div>
                    @endif
                    <div class="gallery-card-overlay">
                        <span>{{ $pet->name }}</span>
                    </div>
                </a>
            @empty
                <div class="section-subtitle" style="margin-top: 1rem;">No pets are available right now. Please check back soon.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection
