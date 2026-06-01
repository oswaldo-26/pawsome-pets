@extends('layouts.app')

@section('title', 'Add Pet – Admin Panel')

@section('content')
<section class="section">
    <div class="section-inner">
        <div class="dashboard-header">
            <div>
                <h1>Add New Pet</h1>
                <p>Add a new pet listing so families can find their forever friend.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn-outline">Back to dashboard</a>
        </div>

        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h2 class="dashboard-card-title">Pet Details</h2>
            </div>

            <form method="POST" action="{{ route('admin.pets.store') }}" enctype="multipart/form-data" class="admin-pet-form">
                @csrf
                @include('admin.pets._form')

                <div class="adoption-form-footer" style="margin-top: 1.5rem; display: flex; gap: 1rem; flex-wrap: wrap;">
                    <button type="submit" class="btn-coral">Save Pet</button>
                    <a href="{{ route('admin.dashboard') }}" class="btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
