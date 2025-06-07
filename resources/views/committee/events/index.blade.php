<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Event (Panitia)</title>
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
                        'dark-gray': '#333333', // For text
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
            @apply bg-gray-50 text-dark-gray; /* Default body background and text color */
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
        /* Style for action buttons in table */
        .action-btn {
            @apply text-sm font-medium transition duration-200 px-2 py-1 rounded;
        }
        .action-btn:hover {
            @apply bg-gray-100;
        }
    </style>
</head>
<body class="p-8">

    <div class="container mx-auto bg-white shadow-lg rounded-xl p-8 mb-8">
        <div class="flex justify-between items-center mb-6 border-b-2 border-primary-blue pb-3">
            <h1 class="text-3xl font-bold text-primary-blue">Manajemen Event <span class="text-secondary-blue">(Panitia)</span></h1>
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

        <div class="mb-6 flex space-x-4">
            <a href="{{ route('committee.events.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-blue text-white font-semibold rounded-lg shadow-md hover:bg-secondary-blue transition duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Buat Event Baru
            </a>
        </div>

        @if ($events->isEmpty())
            <div class="p-6 text-center text-gray-500 bg-light-blue rounded-lg">
                Anda belum membuat event apapun. Klik "Buat Event Baru" untuk memulai!
            </div>
        @else
            <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-primary-blue text-white">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider rounded-tl-lg">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Nama Event</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Lokasi</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Narasumber</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Biaya</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Peserta Max</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider rounded-tr-lg">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($events as $event)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $event->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $event->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $event->location }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $event->speaker }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Rp{{ number_format($event->registration_fee, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $event->max_participants ?? 'Tak Terbatas' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                    <div class="flex flex-wrap gap-2 justify-center">
                                        <a href="{{ route('committee.events.edit', $event) }}" class="action-btn text-primary-blue hover:bg-primary-blue hover:text-white" title="Edit Event">
                                            Edit Event
                                        </a>
                                        <a href="{{ route('committee.events.sub_events.index', $event) }}" class="action-btn text-purple-600 hover:bg-purple-600 hover:text-white" title="Kelola Sesi Event">
                                            Kelola Sesi
                                        </a>
                                        <a href="{{ route('committee.events.attendance.scanner', $event) }}" class="action-btn text-indigo-600 hover:bg-indigo-600 hover:text-white" title="Presensi Event Utama">
                                            Presensi Utama
                                        </a>
                                        <a href="{{ route('committee.events.session_attendance.scanner', $event) }}" class="action-btn text-teal-600 hover:bg-teal-600 hover:text-white" title="Presensi Sesi">
                                            Presensi Sesi
                                        </a>
                                        <a href="{{ route('committee.events.certificates.upload_form', $event) }}" class="action-btn text-orange-600 hover:bg-orange-600 hover:text-white" title="Upload Sertifikat Event Utama">
                                            Upload Sertifikat
                                        </a>
                                        <form action="{{ route('committee.events.delete', $event) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus event ini? Semua data registrasi & sesi akan ikut terhapus!');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn text-red-600 hover:bg-red-600 hover:text-white" title="Hapus Event">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
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