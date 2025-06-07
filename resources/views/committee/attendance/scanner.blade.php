<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scanner Presensi: {{ $event->name }} (Panitia)</title>
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
        /* Custom styles for qr-reader HTML5-QRCODE library */
        #qr-reader {
            width: 100%; /* Make it responsive within its container */
            max-width: 500px; /* Limit max width */
            margin: 0 auto; /* Center it */
            border: 2px solid #004AAD; /* Add a blue border */
            border-radius: 8px; /* Rounded corners */
            overflow: hidden; /* Ensure video stays within bounds */
        }
        #qr-reader-results {
            @apply mt-6 text-center text-lg font-semibold;
        }
        .success { @apply text-green-600; }
        .error { @apply text-red-600; }
        .info { @apply text-blue-600; }
        /* Style for buttons in table */
        .table-action-btn {
            @apply text-sm font-medium transition duration-200 px-2 py-1 rounded;
        }
        .table-action-btn:hover {
            @apply bg-gray-100;
        }
    </style>
</head>
<body class="p-8">

    <div class="container mx-auto bg-white shadow-lg rounded-xl p-8 mb-8">
        <div class="flex justify-between items-center mb-6 border-b-2 border-primary-blue pb-3">
            <h1 class="text-3xl font-bold text-primary-blue">Scanner Presensi <span class="text-secondary-blue">(Event Utama)</span></h1>
            {{-- Tombol Logout untuk Panitia --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-600 text-white font-semibold rounded-lg shadow-md hover:bg-red-700 transition duration-300">
                    Logout
                </button>
            </form>
        </div>

        {{-- Session Status Messages --}}
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
        @if (session('info'))
            <div class="mb-4 p-3 bg-blue-100 text-blue-700 rounded-md text-sm border border-blue-300">
                {{ session('info') }}
            </div>
        @endif

        <h2 class="text-xl font-semibold text-primary-blue mb-4">Event: {{ $event->name }}</h2>
        <p class="mb-6 text-gray-700">Gunakan scanner di bawah ini untuk mencatat kehadiran peserta pada event utama.</p>

        <div id="qr-reader-container" class="flex justify-center items-center flex-col mb-8">
            <div id="qr-reader"></div>
            <div id="qr-reader-results" class="mt-4 text-center text-lg font-semibold"></div>
        </div>

        {{-- PERUBAHAN DI SINI: MENGGUNAKAN URL HARDCODED --}}
        <form id="attendance-form" action="/committee/events/{{ $event->id }}/scan" method="POST" style="display:none;">
            @csrf
            <input type="hidden" name="registration_code" id="registration_code_input">
        </form>

        <hr class="my-8 border-t border-gray-200">

        <h2 class="text-2xl font-bold text-primary-blue mb-4">Daftar Peserta Terdaftar & Kehadiran</h2>
        @if ($registeredParticipants->isEmpty())
            <div class="p-6 text-center text-gray-500 bg-light-blue rounded-lg">
                Tidak ada peserta terdaftar untuk event ini.
            </div>
        @else
            <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-primary-blue text-white">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider rounded-tl-lg">Nama Peserta</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Kode Registrasi</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status Pembayaran</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider rounded-tr-lg">Hadir</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($registeredParticipants as $registration)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $registration->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $registration->user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $registration->registration_code }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($registration->payment_status == 'paid') bg-green-100 text-green-800
                                        @elseif($registration->payment_status == 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($registration->payment_status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $registration->attended ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $registration->attended ? 'Ya' : 'Belum' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="mt-8 text-right">
            <a href="{{ route('committee.events.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg shadow-md hover:bg-gray-300 transition duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Kembali ke Daftar Event
            </a>
        </div>
    </div>

    {{-- Pindahkan semua script di sini, di akhir body --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        function onScanSuccess(decodedText, decodedResult) {
            document.getElementById('registration_code_input').value = decodedText;
            document.getElementById('attendance-form').submit();
        }

        function onScanFailure(error) {
            console.warn(`QR scan error = ${error}`);
            document.getElementById('qr-reader-results').innerHTML = `<span class="error">Gagal membaca QR Code. Pastikan pencahayaan cukup dan QR jelas.</span>`;
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader",
            {
                fps: 10,
                qrbox: {width: 250, height: 250},
            },
            /* verbose=false */
        );

        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script>
</body>
</html>