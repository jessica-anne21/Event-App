<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Pembayaran (Tim Keuangan)</title>
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
        /* Custom scrollbar for better aesthetics */
        body::-webkit-scrollbar {
            width: 8px;
        }
        body::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        body::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        body::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>
<body class="p-8">

    <div class="container mx-auto bg-white shadow-lg rounded-xl p-8 mb-8">
        <div class="flex justify-between items-center mb-6 border-b-2 border-primary-blue pb-3">
            <h1 class="text-3xl font-bold text-primary-blue">Verifikasi Pembayaran <span class="text-secondary-blue">(Tim Keuangan)</span></h1>
            {{-- Tombol Logout untuk Tim Keuangan --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-600 text-white font-semibold rounded-lg shadow-md hover:bg-red-700 transition duration-300">
                    Logout
                </button>
            </form>
        </div>

        {{-- Navigasi antara Pending dan Verified --}}
        <div class="mb-6 flex space-x-4">
            <a href="{{ route('finance.payments.pending') }}" class="px-5 py-2 rounded-lg font-semibold bg-primary-blue text-white shadow-md">
                Pembayaran Tertunda
            </a>
            <a href="{{ route('finance.payments.verified') }}" class="px-5 py-2 rounded-lg font-semibold bg-gray-200 text-gray-700 hover:bg-gray-300 transition duration-200">
                Riwayat Terverifikasi
            </a>
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

        @if ($pendingRegistrations->isEmpty())
            <div class="p-6 text-center text-gray-500 bg-light-blue rounded-lg">
                Tidak ada pembayaran yang menunggu verifikasi saat ini.
            </div>
        @else
            <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-primary-blue text-white">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider rounded-tl-lg">ID Registrasi</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Nama Peserta</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Email Peserta</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Nama Event</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Biaya Event</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Bukti Pembayaran</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider rounded-tr-lg">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($pendingRegistrations as $reg)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $reg->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $reg->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $reg->user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $reg->event->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Rp{{ number_format($reg->event->registration_fee, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    @if ($reg->payment_proof_path)
                                        <a href="{{ asset('storage/' . $reg->payment_proof_path) }}" target="_blank" class="text-primary-blue hover:text-secondary-blue font-medium">Lihat Bukti</a>
                                    @else
                                        <span class="text-gray-500 italic">Belum diunggah</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <form action="{{ route('finance.payments.verify', $reg) }}" method="POST" class="inline-block mr-2">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="text-green-600 hover:text-green-800 transition duration-200" title="Verifikasi Pembayaran">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Verifikasi
                                        </button>
                                    </form>
                                    <form action="{{ route('finance.payments.reject', $reg) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menolak pembayaran ini? Peserta akan diminta mengunggah ulang.');">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition duration-200" title="Tolak Pembayaran">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                            Tolak
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</body>
</html>