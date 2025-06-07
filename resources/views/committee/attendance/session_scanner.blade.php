<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scanner Presensi Sesi: {{ $event->name }}</title>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
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
        #qr-reader {
            width: 100%; max-width: 500px; margin: 0 auto;
            border: 2px solid #004AAD; border-radius: 8px; overflow: hidden;
        }
        #qr-reader-results { @apply mt-6 text-center text-lg font-semibold; }
        .success { @apply text-green-600; }
        .error { @apply text-red-600; }
        .info { @apply text-blue-600; }
        #scan-result-message {
            @apply font-semibold mt-4 text-center;
        }
    </style>
</head>
<body class="p-8">

    <div class="container mx-auto bg-white shadow-lg rounded-xl p-8 mb-8">
        <div class="flex justify-between items-center mb-6 border-b-2 border-primary-blue pb-3">
            <h1 class="text-3xl font-bold text-primary-blue">Scanner Presensi <span class="text-secondary-blue">(Sesi)</span></h1>
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
        <p class="mb-4 text-gray-700">Pilih sesi yang ingin Anda catat kehadirannya:</p>
        <select id="select-sub-event" class="w-full md:w-1/2 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-blue focus:border-primary-blue transition duration-200 mb-6" required>
            <option value="">-- Pilih Sesi --</option>
            @foreach ($subEvents as $subEvent)
                <option value="{{ $subEvent->id }}">{{ $subEvent->name }} ({{ \Carbon\Carbon::parse($subEvent->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($subEvent->end_time)->format('H:i') }} - {{ $subEvent->location }})</option>
            @endforeach
        </select>
        <p id="selected-session-info" class="text-gray-600 text-sm mb-6"></p>

        <h2 class="text-2xl font-bold text-primary-blue mb-4">Scan QR Code Peserta</h2>
        <div id="qr-reader-container" class="flex justify-center items-center flex-col mb-8">
            <div id="qr-reader"></div>
            <div id="qr-reader-results"></div>
            <div id="scan-result-message" class="mt-4 text-center text-lg font-semibold"></div>
        </div>

        <hr class="my-8 border-t border-gray-200">

        <div class="mt-8 text-right">
            <a href="{{ route('committee.events.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg shadow-md hover:bg-gray-300 transition duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Kembali ke Daftar Event
            </a>
        </div>
    </div>

    <script>
        let selectedSubEventId = null;
        const selectSubEvent = document.getElementById('select-sub-event');
        const scanResultMessage = document.getElementById('scan-result-message');
        const selectedSessionInfo = document.getElementById('selected-session-info');

        selectSubEvent.addEventListener('change', function() {
            selectedSubEventId = this.value;
            const selectedText = this.options[this.selectedIndex].text;
            selectedSessionInfo.textContent = selectedSubEventId ? `Anda memilih sesi: ${selectedText}` : '';
            if (selectedSubEventId) {
                scanResultMessage.className = 'info'; // Reset to info style
                scanResultMessage.textContent = 'Siap untuk scan...';
            } else {
                scanResultMessage.className = 'error';
                scanResultMessage.textContent = 'Pilih sesi terlebih dahulu.';
            }
        });

        function onScanSuccess(decodedText, decodedResult) {
            if (!selectedSubEventId) {
                scanResultMessage.className = 'error';
                scanResultMessage.textContent = 'Error: Silakan pilih sesi terlebih dahulu.';
                return;
            }

            scanResultMessage.className = '';
            scanResultMessage.textContent = 'Memproses...';

            // Use Fetch API for AJAX request
            fetch('{{ route('committee.events.scan-session-attendance', $event) }}', { // Correct route name
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    registration_code: decodedText,
                    sub_event_id: selectedSubEventId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    scanResultMessage.className = 'success';
                    scanResultMessage.textContent = 'SUKSES: ' + data.message;
                    // Optional: Refresh participant list if needed, or update status for specific participant
                    // For simplicity, just display message here
                } else if (data.status === 'info') {
                    scanResultMessage.className = 'info';
                    scanResultMessage.textContent = 'INFO: ' + data.message;
                } else {
                    scanResultMessage.className = 'error';
                    scanResultMessage.textContent = 'ERROR: ' + data.message;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                scanResultMessage.className = 'error';
                scanResultMessage.textContent = 'Terjadi kesalahan saat memproses presensi sesi.';
            });
        }

        function onScanFailure(error) {
            // console.warn(`QR scan error = ${error}`);
            // Only show a general error if no specific session is selected
            if (!selectedSubEventId) {
                scanResultMessage.className = 'error';
                scanResultMessage.textContent = 'Pilih sesi terlebih dahulu.';
            } else {
                // This is a common error for no QR code found, might not need to display loudly
                // scanResultMessage.className = 'error';
                // scanResultMessage.textContent = 'Tidak dapat membaca QR Code. Pastikan pencahayaan cukup dan QR jelas.';
            }
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader",
            {
                fps: 10,
                qrbox: {width: 250, height: 250},
            },
            /* verbose=false */
        );

        // Initial rendering of the scanner
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);

        // Initial check for selected session message
        if (!selectedSubEventId) {
            scanResultMessage.textContent = 'Pilih sesi terlebih dahulu.';
            scanResultMessage.className = 'error';
        }
    </script>
</body>
</html>