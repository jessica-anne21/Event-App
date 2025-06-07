<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Presensi</title>
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
        /* Mengatur ukuran dan posisi QR Code agar terlihat menonjol */
        .qr-code-container svg {
            display: block; /* Menghilangkan spasi ekstra di bawah gambar SVG */
            margin: 0 auto; /* Menengahankan SVG */
            width: 100%; /* Memastikan SVG mengisi container */
            max-width: 300px; /* Batasi ukuran maksimum agar tidak terlalu besar */
            height: auto; /* Jaga rasio aspek */
        }
    </style>
</head>
<body class="bg-gradient-to-br from-primary-blue to-secondary-blue flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-md bg-white shadow-xl rounded-xl p-8 text-center transform transition-transform duration-300 ease-in-out hover:scale-105">
        <h1 class="text-3xl font-bold text-primary-blue mb-4">QR Code Presensi</h1>
        <h2 class="text-xl font-semibold text-secondary-blue mb-4">Event: {{ $registration->event->name }}</h2>
        <p class="text-gray-700 mb-6">Silakan tunjukkan QR Code ini kepada Panitia Kegiatan untuk melakukan registrasi ulang.</p>

        <div class="qr-code-container bg-light-blue p-6 rounded-lg shadow-inner inline-block">
            {!! $qrCode !!}
        </div>

        <p class="mt-6 text-gray-800 text-lg font-medium">
            Kode Registrasi: <strong class="text-primary-blue">{{ $registration->registration_code }}</strong>
        </p>

        <a href="{{ route('member.my_registrations') }}" class="inline-flex items-center justify-center mt-8 px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg shadow-md hover:bg-gray-300 transition duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali ke Registrasi Saya
        </a>
    </div>

</body>
</html>