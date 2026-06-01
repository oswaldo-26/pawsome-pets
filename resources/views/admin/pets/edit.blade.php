@extends('layouts.app')

@section('title', 'Edit Pet – Admin Panel')

@section('content')
<section class="section">
    <div class="section-inner">
        <div class="dashboard-header">
            <div>
                <h1>Edit Pet</h1>
                <p>Update pet details and keep your listing fresh for adopters.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn-outline">Back to dashboard</a>
        </div>

        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h2 class="dashboard-card-title">Edit {{ $pet->name }}</h2>
            </div>

            <form method="POST" action="{{ route('admin.pets.update', $pet) }}" enctype="multipart/form-data" class="admin-pet-form">
                @csrf
                @method('PUT')
                @include('admin.pets._form')

                <div class="adoption-form-footer" style="margin-top: 1.5rem; display: flex; gap: 1rem; flex-wrap: wrap;">
                    <button type="submit" class="btn-coral">Update Pet</button>
                    <a href="{{ route('admin.dashboard') }}" class="btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
