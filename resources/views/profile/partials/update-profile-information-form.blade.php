<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile</title>
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                document.getElementById('profile-preview').src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
    <style>
        .profile-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }
        .profile-image {
            width: 128px;
            height: 128px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ccc;
        }
        .upload-btn {
            background-color: #007BFF;
            color: white;
            padding: 6px 12px;
            font-size: 12px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            transition: background 0.2s ease-in-out;
        }
        .upload-btn:hover {
            background-color: #0056b3;
        }
        .hidden-input {
            display: none;
        }
    </style>
</head>
<body>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Profile Information') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                {{ __("Update your account's profile information, email address, and profile picture.") }}
            </p>
        </header>

        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
            @csrf
            @method('patch')

            <!-- Profile Picture Upload -->
            <section class="profile-container">
                <img id="profile-preview" 
                     src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('/default-profile.png') }}" 
                     class="profile-image" 
                     alt="Profile Picture" />
            
                <button type="button" class="upload-btn" onclick="document.getElementById('profile_picture').click()">Change Picture</button>
            
                <input id="profile_picture" name="profile_picture" type="file" class="hidden-input" accept="image/*" onchange="previewImage(event)" />
            </section>

            <!-- Name Input -->
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <!-- Email Input -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2 text-sm text-gray-800">
                        <p>{{ __('Your email address is unverified.') }}</p>
                        <button form="send-verification" 
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Save Button -->
            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Save') }}</x-primary-button>

                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }"
                       x-show="show"
                       x-transition
                       x-init="setTimeout(() => show = false, 2000)"
                       class="text-sm text-gray-600">
                        {{ __('Saved.') }}
                    </p>
                @endif
            </div>
        </form>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const fileInput = document.getElementById("profile_picture");
            const profilePreview = document.getElementById("profile-preview");
            
            fileInput.addEventListener("change", function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function() {
                        profilePreview.src = reader.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
</body>
</html>
