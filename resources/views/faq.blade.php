@extends('layouts.app')

@section('title', 'FAQ – PAWsome Pets')

@section('content')
<section class="section">
    <div class="section-inner">
        <span class="section-eyebrow">FAQ</span>
        <h1 class="section-title">Have questions? We’ve got answers.</h1>
        <p class="section-subtitle">Learn how adoption works, what to expect, and how to give your new pet the best start.</p>

        <div class="faq-grid">
            <div class="faq-list">
                <details class="faq-item" open>
                    <summary class="faq-question">How do I apply to adopt a pet?</summary>
                    <p class="faq-answer">Browse the pet gallery and open the profile for the animal you love. Click “Apply” and complete the application form. We’ll review your request and notify you when a decision is made.</p>
                </details>

                <details class="faq-item">
                    <summary class="faq-question">What does the adoption process include?</summary>
                    <p class="faq-answer">Once you submit an application, our team checks your references and home environment. If approved, we schedule a meet-and-greet and guide you through paperwork, pickup, and first-week support.</p>
                </details>

                <details class="faq-item">
                    <summary class="faq-question">Can I adopt if I live in an apartment?</summary>
                    <p class="faq-answer">Yes — many adopters live in apartments. We match pets to your space, lifestyle, and schedule, and give tips for safe indoor living, exercise, and enrichment.</p>
                </details>

                <details class="faq-item">
                    <summary class="faq-question">What should I bring when I pick up my new pet?</summary>
                    <p class="faq-answer">Bring a collar, leash, carrier or crate, and proof of ID. We also recommend soft bedding and a supply of the food the pet is currently eating to make the transition smoother.</p>
                </details>

                <details class="faq-item">
                    <summary class="faq-question">How do I update my application or ask about status?</summary>
                    <p class="faq-answer">Visit your dashboard after signing in, or contact our support team from the Contact page. We’ll update your application status as soon as there’s news.</p>
                </details>
            </div>

            <aside class="support-card form-card">
                <span class="section-eyebrow">Still need help?</span>
                <h2 class="section-title">Contact our adoption team</h2>
                <p class="section-subtitle">We’re here to answer questions about adoption, applications, and pet care.</p>
                <div class="support-contact-details">
                    <div>
                        <strong>Email</strong>
                        <a href="mailto:hello@pawsomepets.com">hello@pawsomepets.com</a>
                    </div>
                    <div>
                        <strong>Phone</strong>
                        <a href="tel:+18001234567">(800) 123-4567</a>
                    </div>
                    <div>
                        <strong>Hours</strong>
                        <span>Mon–Fri · 9am–6pm</span>
                    </div>
                </div>
                <a href="{{ url('/contact') }}" class="btn-primary">Send us a message</a>
            </aside>
        </div>
    </div>
</section>
@endsection
