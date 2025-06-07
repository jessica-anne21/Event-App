<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Sesi untuk {{ $event->name }}</title>
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
        body { font-family: 'Inter', sans-serif; @apply bg-gray-50 text-dark-gray; }
        input[type="checkbox"]:checked {
            @apply bg-primary-blue border-transparent;
        }
        input[type="checkbox"]:focus {
            outline: none;
            box-shadow: none;
            border-color: transparent;
            ring-color: #004AAD;
        }
    </style>
</head>
<body class="p-8">

    <div class="container mx-auto bg-white shadow-lg rounded-xl p-8 mb-8">
        <h1 class="text-3xl font-bold text-primary-blue mb-6 border-b-2 border-primary-blue pb-3">Pilih Sesi untuk Event: <span class="text-secondary-blue">{{ $event->name }}</span></h1>
        <p class="text-gray-700 mb-6">Anda terdaftar di event utama dengan kode: <strong class="text-primary-blue">{{ $registration->registration_code }}</strong></p>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-md text-sm border border-green-300">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md text-sm border border-red-300">
                {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md text-sm border border-red-300">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('member.registrations.store_selected_sessions', $registration) }}" method="POST">
            @csrf
            <h3 class="text-xl font-semibold text-primary-blue mb-4">Sesi Tersedia:</h3>
            @if ($availableSessions->isEmpty())
                <div class="p-6 text-center text-gray-500 bg-light-blue rounded-lg">
                    Belum ada sesi yang tersedia untuk event ini.
                </div>
            @else
                <div class="space-y-4 mb-6">
                    @foreach ($availableSessions as $session)
                        <div class="flex items-start p-4 border border-gray-200 rounded-lg shadow-sm bg-white">
                            <input type="checkbox"
                                   id="session_{{ $session->id }}"
                                   name="sessions[]"
                                   value="{{ $session->id }}"
                                   class="mt-1 h-5 w-5 text-primary-blue rounded border-gray-300 focus:ring-primary-blue"
                                   {{ in_array($session->id, $selectedSessionIds) ? 'checked' : '' }}
                                   {{ ($session->max_participants && $session->sessionRegistrations->count() >= $session->max_participants && !in_array($session->id, $selectedSessionIds)) ? 'disabled' : '' }}
                            >
                            <label for="session_{{ $session->id }}" class="ml-3 flex-1">
                                <strong class="text-lg text-primary-blue">{{ $session->name }}</strong>
                                <p class="text-sm text-gray-700">
                                    {{ \Carbon\Carbon::parse($session->date)->format('d M Y') }} {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    Lokasi: {{ $session->location }} | Narasumber: {{ $session->speaker }}
                                </p>
                                @if ($session->max_participants)
                                    <p class="text-sm text-gray-600">
                                        Kuota: {{ $session->sessionRegistrations->count() }} / {{ $session->max_participants }}
                                        @if ($session->sessionRegistrations->count() >= $session->max_participants && !in_array($session->id, $selectedSessionIds))
                                            <span class="text-red-600 font-semibold">(Sesi Penuh)</span>
                                        @endif
                                    </p>
                                @endif
                                <p class="text-xs text-gray-500 italic mt-1">{{ $session->description }}</p>
                            </label>
                        </div>
                    @endforeach
                </div>
                <div class="flex items-center justify-between mt-8">
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-primary-blue text-white font-semibold rounded-lg shadow-md hover:bg-secondary-blue transition duration-300 transform hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M5 4a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2H5zm0 2h10v6H5V6zm5 10a1 1 0 01-1-1v-2a1 1 0 112 0v2a1 1 0 01-1 1z" />
                        </svg>
                        Simpan Pilihan Sesi
                    </button>
                    <a href="{{ route('member.my_registrations') }}" class="text-gray-600 hover:text-primary-blue transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Kembali ke Registrasi Saya
                    </a>
                </div>
            @endif
        </form>
    </div>

</body>
</html>