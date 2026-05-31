@extends('layouts.app')

@section('title', 'Contact Us – PAWsome Pets')

@section('content')
<section class="section">
    <div class="section-inner">
        <span class="section-eyebrow">Contact</span>
        <h1 class="section-title">Let’s make this easy.</h1>
        <p class="section-subtitle">Have a question about adoption, your dashboard, or one of our pets? Send us a message and we’ll get back to you soon.</p>

        <div class="contact-grid">
            <aside class="contact-support-card form-card">
                <span class="section-eyebrow">Reach out</span>
                <h2 class="section-title">We’re here to help</h2>
                <p class="section-subtitle">Our team supports adopters and pet lovers every step of the way.</p>

                <div class="contact-details">
                    <div>
                        <strong>Email</strong>
                        <a href="mailto:hello@pawsomepets.com">hello@pawsomepets.com</a>
                    </div>
                    <div>
                        <strong>Phone</strong>
                        <a href="tel:+18001234567">(800) 123-4567</a>
                    </div>
                    <div>
                        <strong>Visit</strong>
                        <span>123 Pet Lane, Happyville</span>
                    </div>
                </div>
            </aside>

            <div class="form-card contact-form-wrapper">
                <form method="POST" action="{{ url('/contact') }}" class="contact-form">
                    @csrf

                    <div class="form-group">
                        <label for="name">Your name</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Jane Doe">
                        @error('name')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Your email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="hello@example.com">
                        @error('email')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input id="subject" name="subject" type="text" value="{{ old('subject') }}" placeholder="Adoption question">
                        @error('subject')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" placeholder="Tell us how we can help...">{{ old('message') }}</textarea>
                        @error('message')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <button type="submit" class="btn-primary">Send message</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
