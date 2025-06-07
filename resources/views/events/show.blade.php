<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->name }}</title>
</head>
<body>
    <h1>{{ $event->name }}</h1>
    <p>Tanggal: {{ $event->date }}</p>
    <p>Waktu: {{ $event->time }}</p>
    <p>Lokasi: {{ $event->location }}</p>
    <p>Narasumber: {{ $event->speaker }}</p>
    <p>Biaya Registrasi: Rp{{ number_format($event->registration_fee, 0, ',', '.') }}</p>
    <p>Maks Peserta: {{ $event->max_participants ?? 'Tidak Terbatas' }}</p>
    <p>Deskripsi: {{ $event->description }}</p>

    @if ($event->poster_path)
        <img src="{{ asset('storage/' . $event->poster_path) }}" alt="Poster Event" width="400">
    @endif

    @auth
        @if (Auth::user()->role === 'member')
            {{-- Form registrasi dan pembayaran akan ada di sini untuk member --}}
            <a href="{{ route('member.events.register', $event) }}">Daftar Event Ini</a>
        @endif
    @else
        <p>Silakan <a href="{{ route('login') }}">Login</a> atau <a href="{{ route('register') }}">Daftar</a> untuk mendaftar event ini.</p>
    @endauth

    <a href="{{ route('events.index') }}">Kembali ke Daftar Event</a>
</body>
</html>