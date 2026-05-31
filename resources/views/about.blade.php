@extends('layouts.app')

@section('title', 'About Us – PAWsome Pets')

@section('content')
<section class="section">
    <div class="section-inner">
        <div class="section-eyebrow">About Us</div>
        <h1 class="section-title">Helping Pets Find Forever Homes</h1>
        <p class="section-subtitle">PAWsome Pets is a community shelter built around compassion, care, and meaningful pet adoption.</p>

        <div class="about-grid">
            <div class="about-card">
                <h2>Our mission</h2>
                <p>We connect loving families with pets in need through safe, friendly adoption experiences. Every animal deserves a warm home and a second chance.</p>
            </div>
            <div class="about-card">
                <h2>How we help</h2>
                <p>From medical care and behavior support to adoption guidance, we make it easy for adopters to meet the right pet, and for pets to thrive while they wait.</p>
            </div>
            <div class="about-card">
                <h2>Our promise</h2>
                <p>Transparent adoption policies, trusted support, and a welcoming shelter environment for both people and animals.</p>
            </div>
        </div>

        <div class="about-cta">
            <div>
                <h2>Ready to meet a new friend?</h2>
                <p>Explore our Pet Gallery or read more about adoption to find the perfect companion.</p>
            </div>
            <div class="about-actions">
                <a href="{{ url('/gallery') }}" class="btn-outline">View Gallery</a>
                <a href="{{ url('/pets') }}" class="btn-coral">Adopt a Pet</a>
            </div>
        </div>
    </div>
</section>
@endsection
