@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 110px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Profile</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Profile Picture Section -->
                        <div class="text-center mb-4">
                            <div class="profile-picture-container mb-3">
                                @if(auth()->user()->profile_picture)
                                    <img src="{{ Storage::url(auth()->user()->profile_picture) }}" 
                                         class="rounded-circle profile-picture" 
                                         alt="Profile Picture"
                                         style="width: 150px; height: 150px; object-fit: cover;">
                                @else
                                    <div class="default-profile-picture rounded-circle d-flex align-items-center justify-content-center"
                                         style="width: 150px; height: 150px; background-color: #e9ecef; margin: 0 auto;">
                                        <i class="bi bi-person-fill" style="font-size: 4rem;"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="mb-3">
                                <label for="profile_picture" class="form-label">Change Profile Picture</label>
                                <input type="file" 
                                       class="form-control @error('profile_picture') is-invalid @enderror" 
                                       id="profile_picture" 
                                       name="profile_picture"
                                       accept="image/*">
                                @error('profile_picture')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <div class="form-text">Maximum file size: 2MB. Supported formats: JPEG, PNG, JPG</div>
                            </div>
                        </div>

                        <!-- Name Field -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', auth()->user()->name) }}" 
                                   required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', auth()->user()->email) }}" 
                                   required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Phone Number Field -->
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" 
                                   class="form-control @error('phone_number') is-invalid @enderror" 
                                   id="phone_number" 
                                   name="phone_number" 
                                   value="{{ old('phone_number', auth()->user()->phone_number) }}">
                            @error('phone_number')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Bio Field -->
                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" 
                                      id="bio" 
                                      name="bio" 
                                      rows="4">{{ old('bio', auth()->user()->bio) }}</textarea>
                            @error('bio')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <hr>

                        <h5 class="mb-3">Change Password (optional)</h5>

                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   name="current_password">
                            @error('current_password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                   name="new_password">
                            @error('new_password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" 
                                   name="new_password_confirmation">
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                            <a href="{{ route('profile.show') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-picture-container {
    position: relative;
    display: inline-block;
}

.profile-picture {
    border: 3px solid #fff;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.profile-picture:hover {
    transform: scale(1.05);
}

.default-profile-picture {
    border: 3px solid #fff;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    color: #6c757d;
}
</style>
@endsection 