<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <title>Login POLBAN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-[#F2F4F7] min-h-screen flex items-center justify-center p-4">
    <div class="flex max-w-4xl w-full rounded-3xl overflow-hidden bg-white shadow-lg">
        <div class="relative w-1/2 min-h-[400px]">
            <img alt="Modern building of POLBAN with glass pyramid roof and blue sky with clouds"
                 class="w-full h-full object-cover" height="400" src="{{ asset('images/foto_gdh.jpg') }}" width="600">
        </div>
        <div class="w-1/2 p-10 flex flex-col justify-center">
            <h2 class="text-[#1A237E] font-bold text-lg mb-8 select-none">Login</h2>
            <div class="flex justify-center mb-8">
                <img alt="POLBAN logo with orange stripes and blue hexagon shape" class="w-28 h-28 object-contain"
                     height="120" src="{{ asset('images/polban_logo.png') }}" width="120">
            </div>
            <!-- Session Status -->
            @if (session('status'))
                <div class="text-green-600 text-xs mb-4">{{ session('status') }}</div>
            @endif
            <form class="space-y-5" method="POST" action="{{ route('login') }}">
                @csrf
                <!-- Email -->
                <div>
                    <label class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none" for="email">Email</label>
                    <input class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]"
                           id="email" placeholder="Masukkan Email" name="email" type="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Password -->
                <div>
                    <label class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none" for="password">Password</label>
                    <input class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]"
                           id="password" placeholder="Masukkan Password" name="password" type="password" required>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Forgot Password -->
                <div class="text-[9px] font-bold text-[#1A237E] mb-4">
                    @if (Route::has('password.request'))
                        Forgot your password?
                        <a class="text-[#1A237E] underline font-semibold" href="{{ route('password.request') }}">Click here!</a>
                    @endif
                </div>
                <!-- Submit Button -->
                <button class="w-full bg-gradient-to-r from-[#1A237E] to-[#3F51B5] text-white font-bold text-xs py-2 rounded-md shadow-md hover:brightness-110 transition"
                        type="submit">LOGIN</button>
            </form>
            <!-- Register Link -->
            <p class="text-[9px] font-bold text-[#1A237E] mt-4 text-center select-none">
                Don't have an account?
                <a class="text-[#1A237E] underline font-semibold" href="{{ route('register') }}">Register</a>
            </p>
        </div>
    </div>
</body>
</html>