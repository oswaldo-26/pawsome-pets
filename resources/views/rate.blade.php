@extends('layouts.app')

@section('title', 'Rate Us – PAWsome Pets')

@section('content')
<section class="section">
    <div class="section-inner">
        <div class="section-eyebrow">Feedback</div>
        <h1 class="section-title">Rate Your Experience</h1>
        <p class="section-subtitle">Your feedback helps us improve — tell us how we did.</p>

        <div class="rate-card">
            @if(session('success'))
                <div class="flash flash-success">✅ {{ session('success') }}</div>
            @endif

            <form action="{{ url('/rate') }}" method="POST">
                @csrf

                <div class="rating" aria-label="Rate PAWsome Pets">
                    <input type="radio" id="star5" name="rating" value="5"><label for="star5">★</label>
                    <input type="radio" id="star4" name="rating" value="4"><label for="star4">★</label>
                    <input type="radio" id="star3" name="rating" value="3"><label for="star3">★</label>
                    <input type="radio" id="star2" name="rating" value="2"><label for="star2">★</label>
                    <input type="radio" id="star1" name="rating" value="1"><label for="star1">★</label>
                </div>

                <div class="form-group">
                    <label for="comments">Comments (optional)</label>
                    <textarea name="comments" id="comments" rows="5" placeholder="Tell us what you liked or what we can do better..."></textarea>
                </div>

                <div class="rate-actions">
                    <button type="submit" class="btn-primary">Send Feedback</button>
                    <a href="{{ url('/') }}" class="btn-outline">Maybe later</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
