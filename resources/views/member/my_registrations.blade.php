<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Event Saya</title>
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
        /* Styling untuk input file agar terintegrasi dengan desain */
        input[type="file"] {
            @apply block w-full text-gray-700
                   file:mr-4 file:py-2 file:px-4
                   file:rounded-lg file:border-0
                   file:text-sm file:font-semibold
                   file:bg-primary-blue file:text-white
                   hover:file:bg-secondary-blue
                   file:transition-colors file:duration-200
                   cursor-pointer;
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
        <div class="flex justify-between items-center mb-6 border-b-2 border-primary-blue pb-3">
            <h1 class="text-3xl font-bold text-primary-blue">Registrasi Event Saya</h1>
            {{-- Tombol Logout untuk Member --}}
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

        @if ($registrations->isEmpty())
            <div class="p-6 text-center text-gray-500 bg-light-blue rounded-lg">
                <p class="mb-4">Anda belum terdaftar di event manapun.</p>
                <a href="{{ route('events.index') }}" class="inline-flex items-center px-4 py-2 bg-primary-blue text-white font-semibold rounded-lg shadow-md hover:bg-secondary-blue transition duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    Cari Event
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($registrations as $reg)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 flex flex-col justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-primary-blue mb-3">{{ $reg->event->name }}</h2>
                            <div class="space-y-1 text-sm text-gray-700 mb-4">
                                {{-- Menampilkan Biaya Registrasi --}}
                                <p>Biaya Event: <strong class="text-primary-blue">Rp{{ number_format($reg->event->registration_fee, 0, ',', '.') }}</strong></p>
                                <p>Status Pembayaran: <strong class="capitalize @if($reg->payment_status == 'paid') text-green-600 @elseif($reg->payment_status == 'pending') text-yellow-600 @else text-red-600 @endif">{{ ucfirst($reg->payment_status) }}</strong></p>
                                <p>Hadir di Event Utama: <strong class="{{ $reg->attended ? 'text-green-600' : 'text-red-600' }}">{{ $reg->attended ? 'Ya' : 'Belum' }}</strong></p>
                            </div>

                            @if ($reg->payment_status === 'pending')
                                {{-- Informasi rekening bank dan harga yang perlu dibayar --}}
                                <div class="bg-light-blue p-4 rounded-lg shadow-inner mb-4">
                                    <p class="text-base text-dark-gray mb-2">
                                        Silakan lakukan pembayaran sebesar: <br>
                                        <span class="text-xl font-bold text-green-700">Rp{{ number_format($reg->event->registration_fee, 0, ',', '.') }}</span>
                                    </p>
                                    <p class="text-base text-gray-700">
                                        Ke rekening berikut:
                                    </p>
                                    <ul class="list-disc list-inside ml-4 text-gray-800 font-semibold text-sm">
                                        <li>Bank: Bank JesJon</li>
                                        <li>Nomor Rekening: 1234567890</li>
                                        <li>Atas Nama: JesJon University Events</li>
                                    </ul>
                                </div>

                                <h3 class="text-lg font-semibold text-primary-blue mb-3">Unggah Bukti Pembayaran</h3>
                                <form action="{{ route('member.registrations.upload_proof', $reg) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                    @csrf
                                    <div>
                                        <label for="payment_proof_{{ $reg->id }}" class="sr-only">Bukti Pembayaran</label>
                                        <input type="file" name="payment_proof" id="payment_proof_{{ $reg->id }}" required>
                                        @error('payment_proof')
                                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <button type="submit" class="w-full px-4 py-2 bg-primary-blue text-white font-semibold rounded-lg shadow-md hover:bg-secondary-blue transition duration-300">
                                        Upload Bukti
                                    </button>
                                </form>
                                @if ($reg->payment_proof_path)
                                    <p class="mt-3 text-sm text-gray-600">Bukti yang sudah diunggah: <a href="{{ asset('storage/' . $reg->payment_proof_path) }}" target="_blank" class="text-primary-blue hover:underline font-medium">Lihat Bukti</a></p>
                                @endif
                            @elseif ($reg->payment_status === 'paid')
                                <p class="text-green-600 font-medium mb-3">Pembayaran telah terverifikasi. Siap untuk presensi!</p>
                                <div class="flex flex-col space-y-2">
                                    @if (!$reg->attended)
                                        <a href="{{ route('member.registrations.qrcode', $reg) }}" class="inline-flex items-center justify-center px-4 py-2 bg-purple-600 text-white font-semibold rounded-lg shadow-md hover:bg-purple-700 transition duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm5-1a1 1 0 011 1v3a1 1 0 01-1 1H9a1 1 0 01-1-1V4zm5 0a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1V4zm-5 8a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1v-3zm5-1a1 1 0 011 1v3a1 1 0 01-1 1H9a1 1 0 01-1-1v-3zm5-1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-3z" clip-rule="evenodd" />
                                            </svg>
                                            Tampilkan QR Code Presensi
                                        </a>
                                    @endif
                                    <a href="{{ route('member.registrations.select_sessions', $reg) }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2h4.586A2 2 0 0113 3.414L16.586 7A2 2 0 0117 8.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 8a1 1 0 011-1h2a1 1 0 110 2H8a1 1 0 01-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        Kelola Pilihan Sesi
                                    </a>
                                </div>

                                <h3 class="text-lg font-semibold text-primary-blue mt-4 mb-2">Sesi yang Anda Pilih:</h3>
                                @if ($reg->sessionRegistrations->isEmpty())
                                    <p class="text-sm text-gray-600 italic">Anda belum memilih sesi apapun.</p>
                                @else
                                    <ul class="list-disc list-inside text-sm text-gray-700 space-y-1">
                                        @foreach ($reg->sessionRegistrations->sortBy('subEvent.start_time') as $sessionReg)
                                            <li>
                                                <strong>{{ $sessionReg->subEvent->name }}</strong> ({{ $sessionReg->subEvent->date }} {{ \Carbon\Carbon::parse($sessionReg->subEvent->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($sessionReg->subEvent->end_time)->format('H:i') }})
                                                @if ($sessionReg->attended_session)
                                                    <span class="text-green-600 font-medium">(Hadir)</span>
                                                @else
                                                    <span class="text-red-600 font-medium">(Belum Hadir)</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif

                                {{-- --- PERUBAHAN DI SINI: TOMBOL DOWNLOAD SERTIFIKAT --- --}}
                                @if ($reg->attended && $reg->certificate_path)
                                    <hr class="my-4 border-gray-200">
                                    <p class="text-green-600 font-medium mb-3">Anda telah hadir di event utama. Sertifikat tersedia!</p>
                                    <a href="{{ route('member.registrations.certificate', $reg) }}" class="inline-flex items-center justify-center px-4 py-2 bg-orange-600 text-white font-semibold rounded-lg shadow-md hover:bg-orange-700 transition duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 01-1-1V6a2 2 0 012-2h5.586a1 1 0 01.707.293L15 7.414A1 1 0 0115.293 8H17a2 2 0 012 2v6a2 2 0 01-2 2H3zm2-1a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1-9a1 1 0 100 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                        </svg>
                                        Unduh Sertifikat Event Utama
                                    </a>
                                @else
                                    <hr class="my-4 border-gray-200">
                                    <p class="text-red-600 font-medium mb-3">Sertifikat belum tersedia. Pastikan Anda hadir atau sertifikat sudah diunggah oleh panitia.</p>
                                @endif
                                {{-- --- AKHIR PERUBAHAN --- --}}

                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</body>
</html>