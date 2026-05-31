@extends('layouts.app')

@section('title', 'PAWsome Pets – Find Your Forever Friend')

@section('content')

    <section class="hero">
        <div class="hero-bg-circles"></div>
        <div class="hero-inner">
            <div class="hero-copy">
                <div class="hero-badge">
                    <div class="hero-badge-dot"></div>
                    Give these CUTIES a chance!
                </div>
                <h1>
                    Find Your<br>
                    <span>Perfect</span><br>
                    Furry Friend
                </h1>
                <p>
                    Every pet here has a story, a personality, and a whole lot of love
                    to give. Browse our rescued animals and open your heart — and your home.
                </p>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <div class="hero-stat-num">{{ $stats['available'] ?? '120' }}+</div>
                        <div class="hero-stat-label">Pets Available</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-num">{{ $stats['adopted'] ?? '850' }}+</div>
                        <div class="hero-stat-label">Happy Adoptions</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-num">5★</div>
                        <div class="hero-stat-label">Shelter Rating</div>
                    </div>
                </div>
            </div>

            <div class="hero-image-stack">
                <div class="hero-img-placeholder">
                    <img src="{{ asset('images/hero-image.jpg') }}" alt="Cute Hero Image of Pets">
                </div>
            </div>
        </div>
    </section>

    <div class="search-card-wrapper">
        <div class="search-card">
            <div class="search-card-header">
                <h3>Quick Match — Find Your Pet</h3>
                <span>Filter by what matters to you</span>
            </div>
            <form action="{{ url('/pets') }}" method="GET">
                <div class="search-fields">
                    <div class="search-field">
                        <label>I'm looking for</label>
                        <select name="species">
                            <option value="">Any Pet</option>
                            <option value="dog"       {{ request('species') == 'dog' ? 'selected' : '' }}>🐶 Dog</option>
                            <option value="cat"       {{ request('species') == 'cat' ? 'selected' : '' }}>🐱 Cat</option>
                            <option value="small_pet" {{ request('species') == 'small_pet' ? 'selected' : '' }}>🐹 Small Pet</option>
                        </select>
                    </div>
                    <div class="search-field">
                        <label>Age Group</label>
                        <select name="age_group">
                            <option value="">Any Age</option>
                            <option value="baby"   {{ request('age_group') == 'baby' ? 'selected' : '' }}>Baby</option>
                            <option value="young"  {{ request('age_group') == 'young' ? 'selected' : '' }}>Young</option>
                            <option value="adult"  {{ request('age_group') == 'adult' ? 'selected' : '' }}>Adult</option>
                            <option value="senior" {{ request('age_group') == 'senior' ? 'selected' : '' }}>Senior</option>
                        </select>
                    </div>
                    <div class="search-field">
                        <label>Gender</label>
                        <select name="gender">
                            <option value="">Any Gender</option>
                            <option value="male"   {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-search">Search Pets →</button>
                </div>
            </form>
        </div>
    </div>

    <section class="section">
        <div class="section-inner">
            <div class="section-eyebrow">Featured Friends</div>
            <h2 class="section-title">Meet Our Lovable Pets</h2>
            <p class="section-subtitle">These wonderful animals are ready for a forever home. Could yours be the one?</p>

            <div class="pets-grid">
                @php
    $featuredPets = $featuredPets ?? [];
                @endphp

                @if(count($featuredPets) > 0)
                    @foreach($featuredPets as $pet)
                        <div class="pet-card">
                            <div style="position:relative;">
                                @if($pet->photo)
                                    <img src="{{ asset('storage/' . $pet->photo) }}"
                                         alt="{{ $pet->name }}"
                                         style="width:100%;height:220px;object-fit:cover;">
                                @else
                                    <div class="pet-card-img-placeholder">
                                        <span>{{ $pet->species === 'dog' ? '🐶' : ($pet->species === 'cat' ? '🐱' : '🐹') }}</span>
                                        <p>No photo yet</p>
                                    </div>
                                @endif
                                <div class="pet-card-status">Available</div>
                            </div>
                            <div class="pet-card-body">
                                <h3>{{ $pet->name }}</h3>
                                <div class="pet-card-meta">
                                    {{ ucfirst($pet->breed ?? $pet->species) }} · {{ ucfirst($pet->age_group) }} · {{ ucfirst($pet->gender) }}
                                </div>
                                <div class="pet-tags">
                                    <span class="pet-tag">{{ ucfirst($pet->species) }}</span>
                                    <span class="pet-tag">{{ ucfirst($pet->age_group) }}</span>
                                    @if($pet->is_vaccinated)  <span class="pet-tag">Vaccinated</span>  @endif
                                    @if($pet->good_with_kids) <span class="pet-tag coral">Good w/ Kids</span> @endif
                                </div>
                                <p class="pet-card-desc">{{ $pet->description ?? 'A wonderful companion waiting for a loving home.' }}</p>
                                <a href="{{ url('/pets/' . $pet->id) }}" class="pet-card-link">
                                    Meet {{ $pet->name }} →
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    @foreach([
            ['🐶', 'Buddy', 'Golden Mix', 'Young · Male', 'dog', 'Loves fetch, great with kids, fully vaccinated.'],
            ['🐱', 'Luna', 'Tabby Cat', 'Adult · Female', 'cat', 'Calm indoor kitty who loves cozy laps.'],
            ['🐹', 'Pip', 'Guinea Pig', 'Baby · Male', 'small_pet', 'Tiny, curious, and irresistibly fluffy.']
        ] as $p)
                    <div class="pet-card">
                        <div style="position:relative;">
                            <div class="pet-card-img-placeholder">
                                <span>{{ $p[0] }}</span>
                                <p>Photo coming soon</p>
                            </div>
                            <div class="pet-card-status">Available</div>
                        </div>
                        <div class="pet-card-body">
                            <h3>{{ $p[1] }}</h3>
                            <div class="pet-card-meta">{{ $p[2] }} · {{ $p[3] }}</div>
                            <div class="pet-tags">
                                <span class="pet-tag">{{ ucfirst($p[4]) }}</span>
                                <span class="pet-tag">Vaccinated</span>
                                <span class="pet-tag coral">Good w/ Kids</span>
                            </div>
                            <p class="pet-card-desc">{{ $p[5] }}</p>
                            <a href="{{ url('/pets') }}" class="pet-card-link">Meet {{ $p[1] }} →</a>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>

            <div class="pets-grid-cta">
                <a href="{{ url('/pets') }}" class="btn-outline">View All Available Pets →</a>
            </div>
        </div>
    </section>

    <section class="section how-section" id="how-it-works">
        <div class="section-inner">
            <div style="text-align:center; max-width:600px; margin:0 auto 1rem;">
                <div class="section-eyebrow">The Process</div>
                <h2 class="section-title">How Adoption Works</h2>
                <p class="section-subtitle" style="margin:0 auto;">
                    Three simple steps stand between you and your new best friend.
                </p>
            </div>

            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-number">01</div>
                    <div class="step-icon-wrap"><span class="step-emoji">🔍</span></div>
                    <h3>Browse & Find</h3>
                    <p>Explore our gallery of available pets. Filter by species, age, and personality to find your perfect match.</p>
                </div>
                <div class="step-card">
                    <div class="step-number">02</div>
                    <div class="step-icon-wrap"><span class="step-emoji">📋</span></div>
                    <h3>Apply & Connect</h3>
                    <p>Submit a simple adoption application. Tell us about your home so we can make the best match.</p>
                </div>
                <div class="step-card">
                    <div class="step-number">03</div>
                    <div class="step-icon-wrap"><span class="step-emoji">🏡</span></div>
                    <h3>Welcome Home</h3>
                    <p>Once approved, bring your new companion home. Our team is here to support you every step of the way.</p>
                </div>
            </div>
        </div>
    </section>

@endsection