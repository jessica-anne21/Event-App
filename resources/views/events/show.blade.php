<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->name }} - Detail Event</title>
    {{-- Memuat Tailwind CSS dari CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Konfigurasi Tailwind untuk warna kustom --}}
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-blue': '#004AAD',
                        'secondary-blue': '#1667C1',
                        'light-blue': '#e0f2f7',
                        'dark-gray': '#333333',
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            @apply bg-gray-50 text-dark-gray;
        }
        .btn-action { /* Untuk tombol Daftar dan Kembali */
            @apply inline-flex items-center justify-center px-6 py-3 font-semibold rounded-full shadow-md transition duration-300 transform hover:-translate-y-0.5;
        }
    </style>
</head>
<body class="p-8">

    <div class="container mx-auto bg-white shadow-xl rounded-xl p-10 pb-12 mb-8">
        <div class="flex justify-between items-center mb-8 border-b-2 border-primary-blue pb-4">
            <h1 class="text-3xl font-bold text-primary-blue">{{ $event->name }} <span class="text-secondary-blue">(Detail Event)</span></h1>
            {{-- --- PERUBAHAN DI SINI: Tombol Logout hanya jika Auth --- --}}
            @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-600 text-white font-semibold rounded-lg shadow-md hover:bg-red-700 transition duration-300">
                    Logout
                </button>
            </form>
            @endauth
            {{-- --- AKHIR PERUBAHAN --- --}}
        </div>

        {{-- Session Status Messages --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-md text-sm border border-green-300">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-md text-sm border border-red-300">
                {{ session('error') }}
            </div>
        @endif
        @if (session('info'))
            <div class="mb-6 p-4 bg-blue-100 text-blue-700 rounded-md text-sm border border-blue-300">
                {{ session('info') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-md text-sm border border-red-300">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-10">
            <div class="md:col-span-1">
                @if($event->poster_path)
                    <img src="{{ asset('storage/' . $event->poster_path) }}" alt="Poster {{ $event->name }}" class="w-full h-auto rounded-lg shadow-xl object-cover">
                @else
                    <div class="w-full h-72 bg-gray-200 rounded-lg shadow-xl flex items-center justify-center text-gray-500 font-semibold text-lg">
                        Poster Tidak Tersedia
                    </div>
                @endif
            </div>

            <div class="md:col-span-1 bg-white p-7 rounded-lg shadow-md">
                <h2 class="text-2xl font-bold text-primary-blue mb-4 pb-2 border-b border-gray-200">Tentang Event</h2>
                <div class="space-y-3 text-gray-700 text-base">
                    <p><strong>Deskripsi:</strong> <span class="text-gray-800">{{ $event->description ?? 'Tidak ada deskripsi.' }}</span></p>
                    <p><strong>Tanggal:</strong> <span class="text-gray-800">{{ \Carbon\Carbon::parse($event->date)->format('d F Y') }}</span></p>
                    <p><strong>Waktu:</strong> <span class="text-gray-800">{{ \Carbon\Carbon::parse($event->time)->format('H:i') }} WIB</span></p>
                    <p><strong>Lokasi:</strong> <span class="text-800">{{ $event->location }}</span></p>
                    <p><strong>Narasumber:</strong> <span class="text-gray-800">{{ $event->speaker }}</span></p>
                    <p class="font-bold text-2xl text-primary-blue pt-3">Biaya Registrasi: Rp {{ number_format($event->registration_fee, 0, ',', '.') }}</p>
                    <p><strong>Kuota Event Utama:</strong> <span class="text-gray-800">{{ $event->max_participants ? $event->max_participants . ' peserta' : 'Tidak Terbatas' }}</span></p>
                </div>

                @auth
                    @php
                        $memberRegistration = Auth::user()->registrations->firstWhere('event_id', $event->id);
                    @endphp

                    @if ($memberRegistration)
                        <div class="info-box bg-light-blue border-primary-blue text-primary-blue mt-8 p-4 rounded-lg">
                            <p class="font-semibold text-lg mb-1">Anda sudah terdaftar di event ini!</p>
                            @if ($memberRegistration->payment_status !== 'paid')
                                <p class="text-base">Status pembayaran: <strong class="capitalize text-yellow-800">{{ $memberRegistration->payment_status }}</strong>. Silakan selesaikan pembayaran Anda di <a href="{{ route('member.my_registrations') }}" class="font-bold underline hover:text-secondary-blue">Registrasi Saya</a>.</p>
                            @else
                                <p class="text-base">Pembayaran sudah terverifikasi.</p>
                                <p class="text-base">Kunjungi <a href="{{ route('member.my_registrations') }}" class="font-bold underline hover:text-secondary-blue">Registrasi Saya</a> untuk mengelola sesi atau melihat QR Code.</p>
                            @endif
                        </div>
                    @else
                        <div class="flex justify-center mt-8">
                            <form action="{{ route('member.events.do_register', $event) }}" method="POST" class="w-full max-w-xs">
                                @csrf
                                <button type="submit" class="btn-action w-full bg-primary-blue text-white hover:bg-secondary-blue">
                                    💳 Daftar & Bayar Sekarang
                                </button>
                            </form>
                        </div>
                    @endif
                @else
                    <div class="info-box bg-light-blue border-primary-blue text-primary-blue mt-8 p-4 rounded-lg">
                        <p class="font-semibold text-lg mb-1">Tertarik untuk bergabung?</p>
                        <p class="text-base">Silakan <strong><a href="{{ route('login') }}" class="font-bold underline hover:text-secondary-blue">Login</a></strong> atau <strong><a href="{{ route('register') }}" class="font-bold underline hover:text-secondary-blue">Daftar</a></strong> untuk mendaftar event ini.</p>
                    </div>
                @endauth
            </div>
        </div>

        <hr class="my-10 border-gray-200">

        <h2 class="text-2xl font-bold text-primary-blue mb-6 border-b border-gray-200 pb-2">Sesi Tersedia dalam Event Ini</h2>
        @if ($event->subEvents->isEmpty())
            <div class="p-6 text-center text-gray-500 bg-light-blue rounded-lg">
                Belum ada sesi yang tersedia untuk event ini.
            </div>
        @else
            <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($event->subEvents->sortBy('start_time') as $session)
                    @php
                        $isFull = ($session->max_participants !== null && $session->session_registrations_count >= $session->max_participants);
                    @endphp
                    <li class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm flex flex-col justify-between transform transition-transform duration-200 hover:shadow-md hover:bg-gray-100">
                        <div>
                            <strong class="text-lg text-dark-gray block mb-1.5">{{ $session->name }}</strong>
                            <p class="text-sm text-gray-700 leading-tight mb-0.5">
                                <span class="font-medium">Waktu:</span> {{ \Carbon\Carbon::parse($session->date)->format('d M Y') }}, {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }} WIB
                            </p>
                            <p class="text-sm text-gray-700 leading-tight">
                                <span class="font-medium">Lokasi:</span> {{ $session->location }} | <span class="font-medium">Narasumber:</span> {{ $session->speaker }}
                            </p>
                            <p class="text-xs text-gray-500 italic mt-2">{{ $session->description ?? 'Tidak ada deskripsi sesi.' }}</p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-100">
                            @if ($session->max_participants !== null)
                                <p class="text-sm text-gray-600">
                                    Kuota: <span class="font-semibold {{ $isFull ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $session->session_registrations_count }} / {{ $session->max_participants }}
                                    </span>
                                    @if ($isFull)
                                        <span class="text-red-600 font-semibold ml-2">(Sesi Penuh)</span>
                                    @endif
                                </p>
                            @else
                                <p class="text-sm text-gray-500">Kuota: Tidak Terbatas</p>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="mt-10 text-right">
            <a href="{{ route('events.index') }}" class="btn-action bg-gray-200 text-gray-700 hover:bg-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Kembali ke Daftar Event
            </a>
        </div>
    </div>

</body>
</html>