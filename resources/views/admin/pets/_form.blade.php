@php
    $pet = $pet ?? null;
@endphp

<div class="form-grid">
    <div class="form-group">
        <label for="name">Pet Name</label>
        <input id="name" name="name" type="text" value="{{ old('name', $pet->name ?? '') }}" required>
        @error('name')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div class="form-group">
        <label for="species">Species</label>
        <select id="species" name="species" required>
            <option value="">Choose species</option>
            <option value="dog" {{ old('species', $pet->species ?? '') === 'dog' ? 'selected' : '' }}>Dog</option>
            <option value="cat" {{ old('species', $pet->species ?? '') === 'cat' ? 'selected' : '' }}>Cat</option>
            <option value="small_pet" {{ old('species', $pet->species ?? '') === 'small_pet' ? 'selected' : '' }}>Small Pet</option>
        </select>
        @error('species')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div class="form-group">
    <label class="form-label" for="breed">Breed <span class="form-required">*</span></label>
        <select
            class="form-input form-select @error('breed') form-input--error @enderror" id="breed" name="breed" required>
            <option value="">Select species first...</option>
        </select>
        @error('breed')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-group">
        <label for="age_group">Age Group</label>
        <select id="age_group" name="age_group" required>
            <option value="">Choose age group</option>
            <option value="baby" {{ old('age_group', $pet->age_group ?? '') === 'baby' ? 'selected' : '' }}>Baby</option>
            <option value="young" {{ old('age_group', $pet->age_group ?? '') === 'young' ? 'selected' : '' }}>Young</option>
            <option value="adult" {{ old('age_group', $pet->age_group ?? '') === 'adult' ? 'selected' : '' }}>Adult</option>
            <option value="senior" {{ old('age_group', $pet->age_group ?? '') === 'senior' ? 'selected' : '' }}>Senior</option>
        </select>
        @error('age_group')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div class="form-group">
        <label for="age_months">Age in Months</label>
        <input id="age_months" name="age_months" type="number" min="0" value="{{ old('age_months', $pet->age_months ?? '') }}">
        @error('age_months')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div class="form-group">
        <label for="gender">Gender</label>
        <select id="gender" name="gender" required>
            <option value="">Choose gender</option>
            <option value="male" {{ old('gender', $pet->gender ?? '') === 'male' ? 'selected' : '' }}>Male</option>
            <option value="female" {{ old('gender', $pet->gender ?? '') === 'female' ? 'selected' : '' }}>Female</option>
        </select>
        @error('gender')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div class="form-group">
        <label for="size">Size</label>
        <select id="size" name="size" required>
            <option value="">Choose size</option>
            <option value="small" {{ old('size', $pet->size ?? '') === 'small' ? 'selected' : '' }}>Small</option>
            <option value="medium" {{ old('size', $pet->size ?? '') === 'medium' ? 'selected' : '' }}>Medium</option>
            <option value="large" {{ old('size', $pet->size ?? '') === 'large' ? 'selected' : '' }}>Large</option>
        </select>
        @error('size')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div class="form-group">
        <label for="status">Availability</label>
        <select id="status" name="status" required>
            <option value="available" {{ old('status', $pet->status ?? '') === 'available' ? 'selected' : '' }}>Available</option>
            <option value="pending" {{ old('status', $pet->status ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="adopted" {{ old('status', $pet->status ?? '') === 'adopted' ? 'selected' : '' }}>Adopted</option>
        </select>
        @error('status')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div class="form-group form-group-full">
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="5">{{ old('description', $pet->description ?? '') }}</textarea>
        @error('description')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div class="form-group">
        <label for="photo">Photo</label>
        <input id="photo" name="photo" type="file" accept="image/*">
        @if(isset($pet) && $pet->photo)
            <p class="form-note">Current photo: <strong>{{ basename($pet->photo) }}</strong></p>
        @endif
        @error('photo')<p class="form-error">{{ $message }}</p>@enderror
    </div>

        <div class="form-group form-group-full">
        <label class="form-label">Pet Attributes</label>
        <p class="form-hint">Select all that apply</p>
    </div>

    <div class="form-group form-check-grid">
        <label class="form-check">
            <input type="checkbox" name="is_vaccinated" value="1" {{ old('is_vaccinated', $pet->is_vaccinated ?? false) ? 'checked' : '' }}>
            <span>Vaccinated</span>
        </label>
        <label class="form-check">
            <input type="checkbox" name="is_neutered" value="1" {{ old('is_neutered', $pet->is_neutered ?? false) ? 'checked' : '' }}>
            <span>Neutered</span>
        </label>
        <label class="form-check">
            <input type="checkbox" name="is_housetrained" value="1" {{ old('is_housetrained', $pet->is_housetrained ?? false) ? 'checked' : '' }}>
            <span>House trained</span>
        </label>
        <label class="form-check">
            <input type="checkbox" name="good_with_kids" value="1" {{ old('good_with_kids', $pet->good_with_kids ?? false) ? 'checked' : '' }}>
            <span>Good with kids</span>
        </label>
        <label class="form-check">
            <input type="checkbox" name="good_with_pets" value="1" {{ old('good_with_pets', $pet->good_with_pets ?? false) ? 'checked' : '' }}>
            <span>Good with pets</span>
        </label>
    </div>
</div>
