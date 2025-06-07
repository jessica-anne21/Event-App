<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JesJon University Events</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* BASE STYLES - PASTIKAN SEMUA CSS ANDA YANG SEBELUMNYA ADA DI SINI */
        /* Pastikan juga Anda memiliki definisi warna kustom Tailwind yang sama */
        * { box-sizing: border-box; font-family: 'Inter', sans-serif; margin: 0; padding: 0; }
        body { background-color: #f9f9f9; padding: 40px; }
        header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .logo { display: flex; align-items: center; gap: 10px; }
        .logo img { width: 40px; }
        .logo strong { color: #004AAD; font-size: 20px; }
        nav a, nav form button { margin: 0 10px; text-decoration: none; color: #333; font-weight: 500; background: none; border: none; cursor: pointer; font-family: inherit; }
        .btn-register { background-color: #004AAD; color: #fff; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; transition: background-color 0.3s ease; }
        .btn-register:hover { background-color: #00357e; }
        .btn-register.bg-red-600 { background-color: #dc2626; }
        .btn-register.hover\:bg-red-700:hover { background-color: #b91c1c; }

        .hero { background: linear-gradient(135deg, #004AAD, #1667C1); color: white; border-radius: 16px; padding: 60px; text-align: center; margin-bottom: 50px; }
        .hero h2 { font-size: 20px; margin-bottom: 10px; }
        .hero h1 { font-size: 48px; font-weight: 700; }

        .section-title { font-size: 28px; font-weight: 700; margin-bottom: 30px; color: #004AAD; }
        .event-list { display: flex; flex-direction: column; gap: 20px; }
        .event-card { display: flex; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); transition: transform 0.2s ease; }
        .event-card:hover { transform: translateY(-5px); }
        .event-card img { width: 120px; height: 120px; object-fit: cover; }
        .event-info { padding: 20px; flex: 1; display: flex; flex-direction: column; justify-content: center; }
        .event-info h3 { font-size: 18px; margin-bottom: 5px; color: #333; }
        .event-info p { color: #666; margin-bottom: 10px; font-size: 14px; }
        .event-info .btn-detail { background-color: #004AAD; color: #fff; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-weight: 600; align-self: flex-start; transition: background-color 0.3s ease; }
        .event-info .btn-detail:hover { background-color: #00357e; }
        
        /* Hapus atau komentari style modal jika tidak ada modal di halaman ini */
        /* .modal, .modal-content, .btn-pay, .btn-close, .info-box { display: none; } */ 
    </style>
</head>
<body>

@if(session('status'))
    <div class="status-message">{{ session('status') }}</div>
@endif

<header>
    <div class="logo">
        <img src="https://img.icons8.com/ios-filled/50/000000/university.png" alt="Logo Universitas">
        <strong>JesJon University</strong>
    </div>
    <nav>
        <a href="{{ route('events.index') }}">Home</a>

        @auth
            <a href="{{ route('member.my_registrations') }}">Registrasi Saya</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn-register bg-red-600 hover:bg-red-700">Logout</button>
            </form>
        @else
            <a class="btn-register" href="{{ route('login') }}">Login</a>
            <a class="btn-register" href="{{ route('register') }}">Daftar</a>
        @endauth
    </nav>
</header>

<section class="hero">
    <h2>BERGABUNG DENGAN KAMI DI</h2>
    <h1>KONFERENSI 2025</h1>
</section>

<section>
    <h2 class="section-title">Daftar Event Terbaru</h2>
    <div class="event-list">
        @if($events->isEmpty())
            <p>Belum ada event yang tersedia saat ini.</p>
        @else
            @foreach($events as $event)
                <div class="event-card">
                    @if($event->poster_path)
                        <img src="{{ asset('storage/' . $event->poster_path) }}" alt="Poster {{ $event->name }}">
                    @else
                        <img src="https://via.placeholder.com/120x120?text=No+Poster" alt="No Poster Available">
                    @endif
                    <div class="event-info">
                        <h3>{{ $event->name }}</h3>
                        <p>Tanggal: {{ \Carbon\Carbon::parse($event->date)->format('d F Y') }}</p>
                        <p>Waktu: {{ \Carbon\Carbon::parse($event->time)->format('H:i') }} WIB</p>
                        <p>Lokasi: {{ $event->location }}</p>
                        <p class="font-semibold text-lg text-primary-blue">
                            Biaya: Rp{{ number_format($event->registration_fee, 0, ',', '.') }}
                        </p>
                        {{-- --- PERUBAHAN DI SINI: MENGUBAH TOMBOL MENJADI LINK LANGSUNG --- --}}
                        <a href="{{ route('events.show', $event) }}" class="btn-detail">
                           Detail & Registrasi
                        </a>
                        {{-- --- AKHIR PERUBAHAN --- --}}
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</section>

</body>
</html>