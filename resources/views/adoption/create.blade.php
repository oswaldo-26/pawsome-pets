@extends('layouts.app')

@section('title', 'Adopt ' . $pet->name . ' – PAWsome Pets')

@section('content')

<section class="section">
    <div class="section-inner">

        <div class="breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            <span>›</span>
            <a href="{{ url('/pets') }}">Pet Gallery</a>
            <span>›</span>
            <a href="{{ url('/pets/' . $pet->id) }}">{{ $pet->name }}</a>
            <span>›</span>
            <span>Adoption Application</span>
        </div>

        <div class="adoption-grid">

            <div class="adoption-pet-card">
                <div class="adoption-pet-img">
                    @if($pet->photo)
                        <img src="{{ asset('storage/' . $pet->photo) }}" alt="{{ $pet->name }}">
                    @else
                        <div class="pet-card-img-placeholder">
                            <span>{{ $pet->species === 'dog' ? '🐶' : ($pet->species === 'cat' ? '🐱' : '🐹') }}</span>
                            <p>No photo yet</p>
                        </div>
                    @endif
                </div>
                <div class="adoption-pet-info">
                    <h2>{{ $pet->name }}</h2>
                    <p class="adoption-pet-meta">
                        {{ ucfirst($pet->breed ?? $pet->species) }} · {{ ucfirst($pet->age_group) }} · {{ ucfirst($pet->gender) }}
                    </p>
                    <div class="pet-tags" style="margin-bottom:1rem;">
                        <span class="pet-tag">{{ ucfirst($pet->species) }}</span>
                        @if($pet->is_vaccinated)  <span class="pet-tag">Vaccinated</span>  @endif
                        @if($pet->is_neutered)    <span class="pet-tag">Neutered</span>    @endif
                        @if($pet->good_with_kids) <span class="pet-tag coral">Good w/ Kids</span> @endif
                        @if($pet->good_with_pets) <span class="pet-tag coral">Good w/ Pets</span> @endif
                    </div>
                    <p class="adoption-pet-desc">{{ $pet->description ?? 'A wonderful companion waiting for a loving home.' }}</p>
                </div>

                <div class="adoption-next-steps">
                    <h4>What happens next?</h4>
                    <div class="adoption-step">
                        <div class="adoption-step-dot">1</div>
                        <p>We review your application within 1–3 business days.</p>
                    </div>
                    <div class="adoption-step">
                        <div class="adoption-step-dot">2</div>
                        <p>You'll receive a notification once a decision is made.</p>
                    </div>
                    <div class="adoption-step">
                        <div class="adoption-step-dot">3</div>
                        <p>If approved, come visit us to bring {{ $pet->name }} home!</p>
                    </div>
                </div>
            </div>

            <div class="adoption-form-wrap">
                <div class="adoption-form-header">
                    <div class="section-eyebrow">Adoption Application</div>
                    <h1 class="section-title">Apply to Adopt {{ $pet->name }}</h1>
                    <p style="color:var(--muted); font-size:0.9rem; margin-top:0.25rem;">
                        Fill out the form below honestly — it helps us make the best match!
                    </p>
                </div>

                <form method="POST"
                      action="{{ url('/adoption/' . $pet->id . '/apply') }}"
                      class="adoption-form">
                    @csrf

                    <div class="form-section">
                        <h3 class="form-section-title">👤 Your Information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="occupation">Occupation</label>
                                <input type="text" id="occupation" name="occupation"
                                       value="{{ old('occupation') }}"
                                       placeholder="e.g. Teacher, Engineer, Student">
                                @error('occupation') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="home_type">Home Type</label>
                                <select id="home_type" name="home_type">
                                    <option value="">Select home type</option>
                                    <option value="house"     {{ old('home_type') == 'house'     ? 'selected' : '' }}>House</option>
                                    <option value="apartment" {{ old('home_type') == 'apartment' ? 'selected' : '' }}>Apartment</option>
                                    <option value="condo"     {{ old('home_type') == 'condo'     ? 'selected' : '' }}>Condo</option>
                                    <option value="other"     {{ old('home_type') == 'other'     ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('home_type') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="form-section-title">🏡 Your Home & Lifestyle</h3>
                        <div class="form-checkboxes">
                            <label class="form-checkbox-item">
                                <input type="checkbox" name="has_yard" value="1"
                                       {{ old('has_yard') ? 'checked' : '' }}>
                                <span>I have a yard or outdoor space</span>
                            </label>
                            <label class="form-checkbox-item">
                                <input type="checkbox" name="has_other_pets" value="1"
                                       {{ old('has_other_pets') ? 'checked' : '' }}>
                                <span>I have other pets at home</span>
                            </label>
                            <label class="form-checkbox-item">
                                <input type="checkbox" name="has_children" value="1"
                                       {{ old('has_children') ? 'checked' : '' }}>
                                <span>I have children at home</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="form-section-title">🐾 Pet Experience</h3>
                        <div class="form-group">
                            <label for="experience">Prior Pet Ownership Experience</label>
                            <textarea id="experience" name="experience"
                                      placeholder="Tell us about any pets you've owned before. First-time owners are welcome too!">{{ old('experience') }}</textarea>
                            @error('experience') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="form-section-title">💛 Why {{ $pet->name }}?</h3>
                        <div class="form-group">
                            <label for="reason">Why do you want to adopt {{ $pet->name }}?</label>
                            <textarea id="reason" name="reason"
                                      placeholder="Share your story — what made you fall in love with {{ $pet->name }}?">{{ old('reason') }}</textarea>
                            @error('reason') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="adoption-form-footer">
                        <a href="{{ url('/pets/' . $pet->id) }}" class="btn-outline">
                            ← Back to {{ $pet->name }}
                        </a>
                        <button type="submit" class="btn-coral">
                            Submit Application 🐾
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</section>

@endsection