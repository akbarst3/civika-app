<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- ID User -->
        <div>
            <x-input-label for="id_user" :value="__('ID User')" />
            <x-text-input id="id_user" class="block mt-1 w-full" type="number" name="id_user" :value="old('id_user')" required autofocus />
            <x-input-error :messages="$errors->get('id_user')" class="mt-2" />
        </div>

        <!-- NIM (Opsional) -->
        <div class="mt-4">
            <x-input-label for="nim" :value="__('NIM')" />
            <x-text-input id="nim" class="block mt-1 w-full" type="text" name="nim" :value="old('nim')" />
            <x-input-error :messages="$errors->get('nim')" class="mt-2" />
        </div>

        <!-- Kode Dosen (Opsional) -->
        <div class="mt-4">
            <x-input-label for="kode_dosen" :value="__('Kode Dosen')" />
            <x-text-input id="kode_dosen" class="block mt-1 w-full" type="text" name="kode_dosen" :value="old('kode_dosen')" />
            <x-input-error :messages="$errors->get('kode_dosen')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>