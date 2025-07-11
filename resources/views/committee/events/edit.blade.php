<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event: {{ $event->name }} (Panitia)</title>
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
        /* Styling untuk input, select, dan textarea focus */
        input:focus, select:focus, textarea:focus {
            outline: none !important;
            border-color: var(--tw-ring-color) !important;
            box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
        }
    </style>
</head>
<body class="p-8">

    <div class="container mx-auto bg-white shadow-lg rounded-xl p-8 mb-8">
        <h1 class="text-3xl font-bold text-primary-blue mb-6 border-b-2 border-primary-blue pb-3">Edit Event: <span class="text-secondary-blue">{{ $event->name }}</span></h1>

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md text-sm border border-red-300">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('committee.events.update', $event) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT') {{-- Penting untuk metode UPDATE --}}

            <div>
                <label for="name" class="block text-sm font-semibold text-primary-blue mb-2">Nama Event:</label>
                <input type="text" id="name" name="name" value="{{ old('name', $event->name) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-blue focus:border-primary-blue transition duration-200">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="date" class="block text-sm font-semibold text-primary-blue mb-2">Tanggal Pelaksanaan:</label>
                    <input type="date" id="date" name="date" value="{{ old('date', $event->date) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-blue focus:border-primary-blue transition duration-200">
                </div>
                <div>
                    <label for="time" class="block text-sm font-semibold text-primary-blue mb-2">Waktu Pelaksanaan:</label>
                    <input type="time" id="time" name="time" value="{{ old('time', $event->time) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-blue focus:border-primary-blue transition duration-200">
                </div>
            </div>

            <div>
                <label for="location" class="block text-sm font-semibold text-primary-blue mb-2">Lokasi:</label>
                <input type="text" id="location" name="location" value="{{ old('location', $event->location) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-blue focus:border-primary-blue transition duration-200">
            </div>

            <div>
                <label for="speaker" class="block text-sm font-semibold text-primary-blue mb-2">Narasumber:</label>
                <input type="text" id="speaker" name="speaker" value="{{ old('speaker', $event->speaker) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-blue focus:border-primary-blue transition duration-200">
            </div>

            <div>
                <label for="poster" class="block text-sm font-semibold text-primary-blue mb-2">Poster Kegiatan (Opsional, maks 2MB):</label>
                <input type="file" id="poster" name="poster" accept="image/*"
                       class="w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-blue file:text-white hover:file:bg-secondary-blue file:transition-colors file:duration-200 cursor-pointer">
                <p class="mt-2 text-xs text-gray-500">Format: JPG, PNG, GIF, SVG. Ukuran maksimal: 2MB.</p>
                @if ($event->poster_path)
                    <div class="mt-4 flex items-center gap-4 p-3 bg-light-blue rounded-lg border border-primary-blue">
                        <p class="text-sm font-medium text-primary-blue">Poster saat ini:</p>
                        <img src="{{ asset('storage/' . $event->poster_path) }}" alt="Poster Event" class="w-24 h-24 object-cover rounded-md shadow">
                        <a href="{{ asset('storage/' . $event->poster_path) }}" target="_blank" class="text-primary-blue hover:underline text-sm font-semibold">Lihat Gambar Penuh</a>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="registration_fee" class="block text-sm font-semibold text-primary-blue mb-2">Biaya Registrasi:</label>
                    <input type="number" id="registration_fee" name="registration_fee" step="0.01" value="{{ old('registration_fee', $event->registration_fee) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-blue focus:border-primary-blue transition duration-200">
                </div>
                <div>
                    <label for="max_participants" class="block text-sm font-semibold text-primary-blue mb-2">Jumlah Maksimal Peserta (Opsional):</label>
                    <input type="number" id="max_participants" name="max_participants" value="{{ old('max_participants', $event->max_participants) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-blue focus:border-primary-blue transition duration-200">
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-semibold text-primary-blue mb-2">Deskripsi Kegiatan (Opsional):</label>
                <textarea id="description" name="description" rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-blue focus:border-primary-blue transition duration-200">{{ old('description', $event->description) }}</textarea>
            </div>

            <div class="flex items-center justify-between mt-8">
                <button type="submit"
                        class="inline-flex items-center px-6 py-3 bg-primary-blue text-white font-semibold rounded-lg shadow-md hover:bg-secondary-blue transition duration-300 transform hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                    </svg>
                    Update Event
                </button>
                <a href="{{ route('committee.events.index') }}" class="text-gray-600 hover:text-primary-blue transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Kembali ke Daftar Event
                </a>
            </div>
        </form>
    </div>

</body>
</html>