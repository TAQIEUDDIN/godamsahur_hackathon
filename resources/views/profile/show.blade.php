@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 110px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">My Profile</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

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
                        <h3>{{ auth()->user()->name }}</h3>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Email:</div>
                        <div class="col-md-8">{{ auth()->user()->email }}</div>
                    </div>

                    @if(auth()->user()->phone_number)
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Phone Number:</div>
                        <div class="col-md-8">{{ auth()->user()->phone_number }}</div>
                    </div>
                    @endif

                    @if(auth()->user()->bio)
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Bio:</div>
                        <div class="col-md-8">{{ auth()->user()->bio }}</div>
                    </div>
                    @endif

                    <div class="text-center mt-4">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>
                    </div>
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
}

.default-profile-picture {
    border: 3px solid #fff;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    color: #6c757d;
}
</style>
@endsection 