<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JesJon University Events</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* Base styles */
        * { box-sizing: border-box; font-family: 'Inter', sans-serif; margin: 0; padding: 0; }
        body { background-color: #f9f9f9; padding: 40px; } /* Warna background umum */

        /* Header */
        header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .logo { display: flex; align-items: center; gap: 10px; }
        .logo img { width: 40px; }
        .logo strong { color: #004AAD; font-size: 20px; } /* Warna biru untuk logo text */
        nav a, nav form button { margin: 0 10px; text-decoration: none; color: #333; font-weight: 500; background: none; border: none; cursor: pointer; font-family: inherit; }
        .btn-register { background-color: #004AAD; color: #fff; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; transition: background-color 0.3s ease; }
        .btn-register:hover { background-color: #00357e; } /* Efek hover untuk tombol biru */
        .btn-register.bg-red-600 { background-color: #dc2626; } /* Logout button red */
        .btn-register.hover\:bg-red-700:hover { background-color: #b91c1c; }

        /* Hero Section */
        .hero { background: linear-gradient(135deg, #004AAD, #1667C1); color: white; border-radius: 16px; padding: 60px; text-align: center; margin-bottom: 50px; }
        .hero h2 { font-size: 20px; margin-bottom: 10px; }
        .hero h1 { font-size: 48px; font-weight: 700; }

        /* Event List Section */
        .section-title { font-size: 28px; font-weight: 700; margin-bottom: 30px; color: #004AAD; } /* Judul bagian biru */
        .event-list { display: flex; flex-direction: column; gap: 20px; }
        .event-card { display: flex; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); transition: transform 0.2s ease; }
        .event-card:hover { transform: translateY(-5px); } /* Efek hover pada card event */
        .event-card img { width: 120px; height: 120px; object-fit: cover; } /* Ukuran gambar event */
        .event-info { padding: 20px; flex: 1; display: flex; flex-direction: column; justify-content: center; }
        .event-info h3 { font-size: 18px; margin-bottom: 5px; color: #333; }
        .event-info p { color: #666; margin-bottom: 10px; font-size: 14px; }
        .event-info .btn-detail { background-color: #004AAD; color: #fff; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-weight: 600; align-self: flex-start; transition: background-color 0.3s ease; }
        .event-info .btn-detail:hover { background-color: #00357e; }

        /* Modal Styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            inset: 0; /* Cover the whole viewport */
            background: rgba(0, 0, 0, 0.6); /* Slightly darker overlay */
            justify-content: center;
            align-items: center;
            z-index: 1000;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            animation: slideUp 0.3s ease;
            position: relative; /* Needed for absolute positioning of close button if used */
        }

        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-content h3 { font-size: 24px; font-weight: 700; margin-bottom: 15px; color: #004AAD; } /* Judul modal biru */
        .modal-body p { margin-bottom: 8px; line-height: 1.5; }
        .modal-body strong { color: #333; }

        .btn-pay {
            background: linear-gradient(to right, #004AAD, #1667C1); /* Gradient biru untuk tombol bayar */
            color: white;
            font-weight: 600;
            padding: 12px;
            border-radius: 10px;
            border: none;
            width: 100%;
            margin-top: 20px; /* Lebih banyak spasi */
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 16px;
        }

        .btn-pay:hover {
            background: #00388a; /* Solid biru lebih gelap saat hover */
            transform: scale(1.02);
        }

        .btn-close {
            margin-top: 15px;
            background: transparent;
            color: #666;
            border: none;
            font-size: 14px;
            width: 100%;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .btn-close:hover {
            color: #004AAD;
        }

        .info-box { /* Mengganti kelas bg-yellow-100 dll. jadi info-box */
            background-color: #e0f2f7; /* Biru muda */
            border-left: 4px solid #004AAD; /* Garis border biru tua */
            color: #004AAD; /* Teks biru tua */
            padding: 15px;
            font-size: 14px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .info-box a {
            color: #004AAD; /* Link dalam info box */
            font-weight: 600;
            text-decoration: underline;
        }
        .info-box a:hover {
            color: #00357e;
        }

        /* Status message for sessions */
        .status-message {
            background-color: #d1e7dd; /* Light green for success */
            color: #0f5132; /* Dark green text */
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
            border: 1px solid #badbcc;
        }
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
                    {{-- Pastikan `poster_path` sesuai dengan nama kolom di database Anda --}}
                    @if($event->poster_path)
                        <img src="{{ asset('storage/' . $event->poster_path) }}" alt="Poster {{ $event->name }}">
                    @else
                        {{-- Placeholder jika tidak ada poster --}}
                        <img src="https://via.placeholder.com/120x120?text=No+Poster" alt="No Poster Available">
                    @endif
                    <div class="event-info">
                        <h3>{{ $event->name }}</h3>
                        <p>Tanggal: {{ \Carbon\Carbon::parse($event->date)->format('d F Y') }}</p>
                        <p>Waktu: {{ \Carbon\Carbon::parse($event->time)->format('H:i') }} WIB</p>
                        <p>Lokasi: {{ $event->location }}</p>
                        <a href="javascript:void(0)"
                           class="btn-detail"
                           data-event='@json($event)'>
                           Detail & Registrasi
                        </a>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</section>

<div id="eventModal" class="modal">
    <div class="modal-content">
        <h3 id="modalTitle">Judul Event</h3>
        
        <div class="modal-body">
            {{-- Konten detail event akan dimasukkan di sini oleh JavaScript --}}
        </div>

        @auth
            {{-- Jika user sudah login, tampilkan form pembayaran --}}
            <form action="{{ route('member.events.do_register', '__EVENT_ID__') }}" method="POST" id="registrationForm">
                @csrf
                <input type="hidden" name="event_id" id="eventInput">
                <button type="submit" class="btn-pay">💳 Daftar & Bayar Sekarang</button>
            </form>
        @else
            {{-- Jika user belum login, tampilkan pesan ajakan login/daftar --}}
            <div class="info-box">
                <p><strong><a href="{{ route('login') }}">Login</a></strong> atau <strong><a href="{{ route('register') }}">Daftar</a></strong> untuk mendaftar dan melakukan pembayaran.</p>
            </div>
        @endauth

        <button onclick="closeModal()" class="btn-close">Tutup ✖</button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const buttons = document.querySelectorAll('.btn-detail');
        const modal = document.getElementById('eventModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalBody = document.querySelector('.modal-body');
        const eventInput = document.getElementById('eventInput');
        const registrationForm = document.getElementById('registrationForm');

        buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                const event = JSON.parse(btn.dataset.event);
                modalTitle.textContent = event.name;
                
                modalBody.innerHTML = `
                    <p><strong>Deskripsi:</strong> ${event.description || 'Tidak ada deskripsi.'}</p>
                    <p><strong>Tanggal:</strong> ${new Date(event.date).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
                    <p><strong>Waktu:</strong> ${event.time} WIB</p>
                    <p><strong>Lokasi:</strong> ${event.location}</p>
                    <p><strong>Narasumber:</strong> ${event.speaker}</p>
                    <p><strong>Biaya Registrasi:</strong> Rp ${Number(event.registration_fee).toLocaleString('id-ID')}</p>
                    <p><strong>Kuota:</strong> ${event.max_participants ? event.max_participants + ' peserta' : 'Tidak Terbatas'}</p>
                `;
                
                // Update form action for registration
                if (registrationForm) {
                    registrationForm.action = registrationForm.action.replace('__EVENT_ID__', event.id);
                    eventInput.value = event.id; // Set hidden input value to event ID
                }

                modal.style.display = 'flex';
            });
        });
    });

    function closeModal() {
        document.getElementById('eventModal').style.display = 'none';
        // Reset form action when modal is closed
        const registrationForm = document.getElementById('registrationForm');
        if (registrationForm) {
            registrationForm.action = registrationForm.action.replace(/\d+$/, '__EVENT_ID__');
        }
    }
</script>

</body>
</html>