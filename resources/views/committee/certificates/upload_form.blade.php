<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Sertifikat: {{ $event->name }} (Panitia)</title>
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
        input[type="file"]:focus {
            outline: none !important;
            border-color: var(--tw-ring-color) !important;
            box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
        }
        /* Style untuk input file agar terintegrasi dengan desain */
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
    </style>
</head>
<body class="p-8">

    <div class="container mx-auto bg-white shadow-lg rounded-xl p-8 mb-8">
        <h1 class="text-3xl font-bold text-primary-blue mb-6 border-b-2 border-primary-blue pb-3">Upload Sertifikat untuk Event: <span class="text-secondary-blue">{{ $event->name }}</span></h1>

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
        {{-- Validation Error Messages --}}
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md text-sm border border-red-300">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <p class="mb-6 text-gray-700">Unggah sertifikat dalam format PDF untuk peserta yang sudah tercatat hadir.</p>

        <form action="{{ route('committee.events.certificates.upload', $event) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <h3 class="text-xl font-semibold text-primary-blue mb-4">Peserta yang Hadir dan Belum Punya Sertifikat</h3>

            @if ($attendedParticipants->isEmpty())
                <div class="p-6 text-center text-gray-500 bg-light-blue rounded-lg">
                    Tidak ada peserta yang tercatat hadir atau semua sudah memiliki sertifikat untuk event ini.
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($attendedParticipants as $registration)
                        @if (!$registration->certificate_path)
                            <div class="p-4 border border-gray-200 rounded-lg shadow-sm bg-white">
                                <label for="certificate_{{ $registration->id }}" class="block text-sm font-medium text-gray-700 mb-2 cursor-pointer">
                                    <strong class="text-primary-blue">{{ $registration->user->name }}</strong> ({{ $registration->user->email }})
                                    <input type="file" name="certificates[]" id="certificate_{{ $registration->id }}" accept="application/pdf" class="mt-2">
                                    <input type="hidden" name="registration_ids[]" value="{{ $registration->id }}">
                                </label>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="mt-8">
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-primary-blue text-white font-semibold rounded-lg shadow-md hover:bg-secondary-blue transition duration-300 transform hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 01-1-1V6a2 2 0 012-2h4a2 2 0 012 2v1H9a1 1 0 000 2h1a1 1 0 001 1h2a1 1 0 001-1V6a2 2 0 012-2h2a2 2 0 012 2v10a2 2 0 01-2 2H3z" clip-rule="evenodd" />
                        </svg>
                        Unggah Semua Sertifikat Terpilih
                    </button>
                </div>
            @endif
        </form>

        <hr class="my-8 border-t border-gray-200">

        <h2 class="text-2xl font-bold text-primary-blue mb-4">Status Sertifikat Peserta</h2>
        <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-primary-blue text-white">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider rounded-tl-lg">Nama Peserta</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Email</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status Kehadiran</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status Sertifikat</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider rounded-tr-lg">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($attendedParticipants as $registration)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $registration->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $registration->user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $registration->attended ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $registration->attended ? 'Hadir' : 'Tidak Hadir' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $registration->certificate_path ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $registration->certificate_path ? 'Ada' : 'Belum Ada' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                @if ($registration->certificate_path)
                                    <a href="{{ route('member.registrations.certificate', $registration) }}" target="_blank" class="text-primary-blue hover:text-secondary-blue transition duration-200 inline-flex items-center" title="Download Sertifikat">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 01-1-1V6a2 2 0 012-2h5.586a1 1 0 01.707.293L15 7.414A1 1 0 0115.293 8H17a2 2 0 012 2v6a2 2 0 01-2 2H3zm2-1a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1-9a1 1 0 100 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                        </svg>
                                        Download
                                    </a>
                                @else
                                    <span class="text-gray-500 italic">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-8 text-right">
            <a href="{{ route('committee.events.index') }}" class="text-gray-600 hover:text-primary-blue transition duration-200 inline-flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Kembali ke Daftar Event
            </a>
        </div>
    </div>

</body>
</html>