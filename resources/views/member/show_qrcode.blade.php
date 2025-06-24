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
        .qr-code-image { /* Kelas untuk img tag */
            display: block;
            margin: 0 auto;
            max-width: 300px;
            height: auto;
            border-radius: 8px; /* Sudut membulat untuk gambar */
            box-shadow: 0 4px 12px rgba(0,0,0,0.1); /* Bayangan */
        }
    </style>
</head>
<body class="bg-gradient-to-br from-primary-blue to-secondary-blue flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-md bg-white shadow-xl rounded-xl p-8 text-center transform transition-transform duration-300 ease-in-out hover:scale-105">
        <h1 class="text-3xl font-bold text-primary-blue mb-4">QR Code Presensi</h1>
        <h2 class="text-xl font-semibold text-secondary-blue mb-4">Event: {{ $registration->event->name }}</h2>
        <p class="text-gray-700 mb-6">Silakan tunjukkan QR Code ini kepada Panitia Kegiatan untuk melakukan registrasi ulang.</p>

        <div class="bg-light-blue p-6 rounded-lg shadow-inner inline-block">
            {{-- --- PERUBAHAN DI SINI: Menggunakan $qrCodePath untuk gambar --- --}}
            <img src="{{ asset('storage/' . $qrCodePath) }}" alt="QR Code Presensi {{ $registration->registration_code }}" class="qr-code-image">
            {{-- --- AKHIR PERUBAHAN --- --}}
        </div>

        <p class="mt-6 text-gray-800 text-lg font-medium">
            Kode Registrasi: <strong class="text-primary-blue">{{ $registration->registration_code }}</strong>
        </p>

        <a href="{{ asset('storage/' . $qrCodePath) }}" download="qrcode_presensi_{{ $registration->registration_code }}.png"
           class="inline-flex items-center justify-center mt-6 px-6 py-3 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition duration-300 transform hover:-translate-y-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 17a1 1 0 01-1-1V6a2 2 0 012-2h5.586a1 1 0 01.707.293L15 7.414A1 1 0 0115.293 8H17a2 2 0 012 2v6a2 2 0 01-2 2H3zM6 10a1 1 0 100 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
            </svg>
            Unduh Gambar QR Code
        </a>

        <a href="{{ route('member.my_registrations') }}" class="inline-flex items-center justify-center mt-4 px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg shadow-md hover:bg-gray-300 transition duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali ke Registrasi Saya
        </a>
    </div>

</body>
</html>