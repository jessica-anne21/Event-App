<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JesJon University Events</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* BASE STYLES - TETAPKAN SEMUA CSS ANDA YANG SEBELUMNYA ADA DI SINI */
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

        /* Carousel Specific Styles */
        .carousel-container {
            position: relative;
            overflow: hidden;
            border-radius: 16px;
            margin-bottom: 50px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .carousel-slides {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        .carousel-slide {
            flex: 0 0 100%;
            position: relative;
            height: 320px; /* --- PERUBAHAN: Tinggi carousel dari 400px menjadi 320px --- */
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            padding: 20px;
        }
        .carousel-slide::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.7));
            z-index: 1;
        }
        .carousel-content {
            position: relative;
            z-index: 2;
            max-width: 80%;
        }
        .carousel-content h2 {
            font-size: 2rem; /* --- PERUBAHAN: text-3xl (dari 2.5rem/4xl) --- */
            font-weight: 700;
            margin-bottom: 0.5rem; /* --- PERUBAHAN: mb-2 (dari 0.75rem/mb-3) --- */
        }
        .carousel-content p {
            font-size: 1.125rem; /* --- PERUBAHAN: text-lg (dari 1.25rem/xl) --- */
            margin-bottom: 1rem; /* --- PERUBAHAN: mb-4 (dari 1.5rem/mb-6) --- */
        }
        .carousel-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0,0,0,0.5);
            color: white;
            padding: 1rem 0.5rem;
            border-radius: 8px;
            cursor: pointer;
            z-index: 10;
            transition: background-color 0.3s;
        }
        .carousel-nav-btn:hover {
            background-color: rgba(0,0,0,0.7);
        }
        .carousel-nav-btn.left { left: 1rem; }
        .carousel-nav-btn.right { right: 1rem; }
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

@if ($events->isNotEmpty())
    <section class="carousel-container">
        <div class="carousel-slides" id="carouselSlides">
            @foreach ($events->take(5) as $event) {{-- Ambil maksimal 5 event untuk carousel --}}
                <div class="carousel-slide" style="background-image: url('{{ $event->poster_path ? asset('storage/' . $event->poster_path) : 'https://via.placeholder.com/1200x320?text=Event+Poster' }}');"> {{-- placeholder menyesuaikan tinggi baru --}}
                    <div class="carousel-content">
                        <h2>{{ $event->name }}</h2>
                        <p>{{ \Carbon\Carbon::parse($event->date)->format('d F Y') }} - {{ $event->location }}</p>
                        <a href="{{ route('events.show', $event) }}" class="btn-register">Lihat Detail & Daftar</a>
                    </div>
                </div>
            @endforeach
        </div>
        @if ($events->count() > 1)
            <button class="carousel-nav-btn left" onclick="moveSlide(-1)">&#10094;</button>
            <button class="carousel-nav-btn right" onclick="moveSlide(1)">&#10095;</button>
        @endif
    </section>
@else
    <section class="hero">
        <h2>BERGABUNG DENGAN KAMI DI</h2>
        <h1>KONFERENSI 2025</h1>
    </section>
@endif

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
                        <a href="{{ route('events.show', $event) }}" class="btn-detail">
                           Detail & Registrasi
                        </a>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</section>

{{-- Script untuk Carousel --}}
<script>
    let currentSlide = 0;
    const slidesContainer = document.getElementById('carouselSlides');
    const slides = document.querySelectorAll('.carousel-slide');
    const totalSlides = slides.length;

    function updateCarousel() {
        if (slidesContainer) {
            slidesContainer.style.transform = `translateX(${-currentSlide * 100}%)`;
        }
    }

    function moveSlide(direction) {
        currentSlide += direction;
        if (currentSlide < 0) {
            currentSlide = totalSlides - 1;
        } else if (currentSlide >= totalSlides) {
            currentSlide = 0;
        }
        updateCarousel();
    }

    // Auto-slide functionality
    let autoSlideInterval;
    if (totalSlides > 1) {
        autoSlideInterval = setInterval(() => {
            moveSlide(1);
        }, 5000); // Ganti slide setiap 5 detik
    }

    // Optional: Stop auto-slide on user interaction (e.g., hover over carousel)
    // const carouselContainer = document.querySelector('.carousel-container');
    // if (carouselContainer) {
    //     carouselContainer.addEventListener('mouseenter', () => clearInterval(autoSlideInterval));
    //     carouselContainer.addEventListener('mouseleave', () => {
    //         if (totalSlides > 1) {
    //             autoSlideInterval = setInterval(() => {
    //                 moveSlide(1);
    //             }, 5000);
    //         }
    //     });
    // }

    // Initial load
    updateCarousel();
</script>

</body>
</html>