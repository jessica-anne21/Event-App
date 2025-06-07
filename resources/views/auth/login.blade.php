<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - JesJon University Events</title>
    {{-- Memuat Tailwind CSS dari CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Konfigurasi Tailwind untuk warna kustom jika perlu --}}
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-blue': '#004AAD', // Warna biru utama
                        'secondary-blue': '#1667C1', // Warna biru sekunder untuk gradient/hover
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Gaya dasar untuk memastikan font Inter diterapkan */
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Tambahan gaya untuk fokus input, agar terlihat lebih halus */
        input:focus {
            outline: none !important;
            border-color: var(--tw-ring-color) !important;
            box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-primary-blue to-secondary-blue flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-md bg-white shadow-xl rounded-xl p-8 transform hover:scale-105 transition-transform duration-300 ease-in-out">
        <div class="text-center mb-6">
            <img src="https://img.icons8.com/ios-filled/50/000000/university.png" alt="Logo Universitas" class="mx-auto h-12 w-auto mb-3">
            <h2 class="text-3xl font-bold text-primary-blue">Login</h2>
            <p class="text-gray-600 text-sm">Masuk untuk melanjutkan ke Event JesJon University</p>
        </div>

        {{-- Session Status --}}
        @if (session('status'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-md text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="mb-4">
                <label for="email" class="block text-sm font-semibold text-primary-blue mb-2">Email</label>
                <input id="email"
                       type="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autofocus
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-blue focus:border-primary-blue transition duration-200 ease-in-out placeholder-gray-400 text-gray-800">
                @error('email')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-6">
                <label for="password" class="block text-sm font-semibold text-primary-blue mb-2">Password</label>
                <input id="password"
                       type="password"
                       name="password"
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-blue focus:border-primary-blue transition duration-200 ease-in-out placeholder-gray-400 text-gray-800">
                @error('password')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember Me (Optional, jika Anda ingin menambahkan) --}}
            {{-- <div class="mb-6 flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-primary-blue shadow-sm focus:ring-primary-blue">
                <label for="remember_me" class="ml-2 block text-sm text-gray-900">Ingat Saya</label>
            </div> --}}

            {{-- Submit Button --}}
            <div class="mb-4">
                <button type="submit"
                        class="w-full bg-primary-blue hover:bg-secondary-blue text-white font-bold py-3 px-4 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-0.5 hover:shadow-lg">
                    Masuk
                </button>
            </div>
        </form>

        <p class="text-sm text-center text-gray-600 mt-6">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-primary-blue font-semibold hover:underline transition duration-200">Daftar Sekarang</a>
        </p>
    </div>

</body>
</html>