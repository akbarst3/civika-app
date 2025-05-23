<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <title>Register POLBAN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .fade-enter-active, .fade-leave-active {
            transition: all 0.5s ease;
        }
        .fade-enter-from, .fade-leave-to {
            opacity: 0;
            transform: translateX(20px);
        }
    </style>
</head>
<body class="bg-[#F2F4F7] min-h-screen flex items-center justify-center p-4">
    <div class="flex max-w-4xl w-full rounded-3xl overflow-hidden bg-white shadow-lg transition-all duration-500 ease-in-out">
        <div class="relative w-1/2 min-h-[400px]">
            <img alt="Modern building of POLBAN with glass pyramid roof and blue sky with clouds"
                 class="w-full h-full object-cover" height="400" src="{{ asset('images/foto_gdh2.jpg') }}" width="600">
        </div>
        <div class="w-1/2 p-10 flex flex-col justify-center">
            <h2 class="text-[#1A237E] font-bold text-lg mb-6 select-none text-center">Register</h2>            
            <form class="space-y-5" method="POST" action="{{ route('register') }}">
                @csrf                
                <!-- NIM -->
                <div>
                    <label class="block text-[10px] font-bold text-[#1A237E] mb-1" for="nim">NIM</label>
                    <input class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]"
                           id="nim" placeholder="Masukkan NIM" name="nim" type="text" value="{{ old('nim') }}">
                    @error('nim')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                 <!-- Kode Dosen -->
                 <div>
                    <label class="block text-[10px] font-bold text-[#1A237E] mb-1" for="kode_dosen">Kode Dosen (opsional untuk kebutuhan testing create akun dosen)</label>
                    <input class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]"
                           id="kode_dosen" placeholder="opsional" name="kode_dosen" type="text" value="{{ old('kode_dosen') }}">
                    @error('kode_dosen')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Email -->
                <div>
                    <label class="block text-[10px] font-bold text-[#1A237E] mb-1" for="email">Email</label>
                    <input class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]"
                           id="email" placeholder="Masukkan Email" name="email" type="email" value="{{ old('email') }}" required>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Password -->
                <div>
                    <label class="block text-[10px] font-bold text-[#1A237E] mb-1" for="password">Password</label>
                    <input class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]"
                           id="password" placeholder="Masukkan Password" name="password" type="password" required>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Confirm Password -->
                <div>
                    <label class="block text-[10px] font-bold text-[#1A237E] mb-1" for="password_confirmation">Konfirmasi Password</label>
                    <input class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]"
                           id="password_confirmation" placeholder="Masukkan Ulang Password" name="password_confirmation" type="password" required>
                    @error('password_confirmation')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <label class="block text-[10px] font-bold text-[#1A237E] mb-1" for="password_confirmation">*Apabila NIM & Kode Dosen kosong, maka role = Tata Usaha (kebutuhan testing)</label>
                <button class="w-full bg-gradient-to-r from-[#1A237E] to-[#3F51B5] text-white font-bold text-xs py-2 rounded-md shadow-md hover:brightness-110 transition" type="submit">
                    REGISTER
                </button>
            </form>
            <!-- Login Link -->
            <p class="text-[9px] font-bold text-[#1A237E] mt-4 text-center select-none">
                Already have an account?
                <a class="text-[#1A237E] underline font-semibold" href="{{ route('login') }}">Login</a>
            </p>
        </div>        
    </div>
</body>
</html>
