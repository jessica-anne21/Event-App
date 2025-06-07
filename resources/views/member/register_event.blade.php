<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Event: {{ $event->name }}</title>
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
        /* Gaya dasar untuk memastikan font Inter diterapkan */
        body {
            font-family: 'Inter', sans-serif;
            @apply bg-gray-50 text-dark-gray;
        }
        /* Styling untuk input dan select focus */
        input:focus, select:focus, textarea:focus {
            outline: none !important;
            border-color: var(--tw-ring-color) !important;
            box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
        }
    </style>
</head>
<body class="p-8">

    <div class="container mx-auto bg-white shadow-lg rounded-xl p-8 mb-8">
        <h1 class="text-3xl font-bold text-primary-blue mb-6 border-b-2 border-primary-blue pb-3">Daftar Event: <span class="text-secondary-blue">{{ $event->name }}</span></h1>

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
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md text-sm border border-red-300">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-light-blue p-6 rounded-lg shadow-inner mb-6">
            <h2 class="text-xl font-bold text-primary-blue mb-3">Detail Pembayaran</h2>
            <p class="text-lg text-dark-gray mb-2">Untuk mendaftar di event **"{{ $event->name }}"**, Anda perlu melakukan pembayaran.</p>
            <p class="text-2xl font-bold text-green-700 mb-4">
                Total Biaya Registrasi: Rp{{ number_format($event->registration_fee, 0, ',', '.') }}
            </p>
            <p class="text-base text-gray-700">
                Silakan lakukan transfer ke rekening berikut:
            </p>
            <ul class="list-disc list-inside ml-4 text-gray-800 font-semibold">
                <li>Bank: Bank JesJon</li>
                <li>Nomor Rekening: 1234567890</li>
                <li>Atas Nama: JesJon University Events</li>
            </ul>
            <p class="text-sm text-gray-600 mt-4">Setelah melakukan transfer, Anda dapat mengunggah bukti pembayaran di halaman "Registrasi Saya".</p>
        </div>

        <form action="{{ route('member.events.do_register', $event) }}" method="POST">
            @csrf
            <div class="flex items-center justify-between mt-8">
                <button type="submit"
                        class="inline-flex items-center px-6 py-3 bg-primary-blue text-white font-semibold rounded-lg shadow-md hover:bg-secondary-blue transition duration-300 transform hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    Konfirmasi Pendaftaran
                </button>
                <a href="{{ route('events.show', $event) }}" class="text-gray-600 hover:text-primary-blue transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Kembali ke Detail Event
                </a>
            </div>
        </form>
    </div>

</body>
</html>