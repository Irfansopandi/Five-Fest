@extends('v_layouts.app')

@section('title', 'Syarat & Ketentuan')

@section('content')
<div class="container py-5 mt-5">
    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
        <h2 class="fw-bold mb-4 text-center">Syarat & Ketentuan Five Fest</h2>
        
        <div class="text-muted" style="line-height: 1.8;">
            <p>Selamat datang di Five Fest. Dengan mendaftar dan menggunakan layanan kami, Anda menyetujui syarat dan ketentuan berikut:</p>
            
            <h5 class="fw-bold text-dark mt-4">1. Pendaftaran Akun</h5>
            <ul>
                <li>Pengguna wajib memberikan informasi yang akurat dan valid.</li>
                <li>Satu email hanya dapat digunakan untuk satu akun.</li>
                <li>Pengguna bertanggung jawab atas keamanan kata sandi akun masing-masing.</li>
            </ul>

            <h5 class="fw-bold text-dark mt-4">2. Pembelian Tiket</h5>
            <ul>
                <li>Tiket yang sudah dibeli tidak dapat dibatalkan atau dikembalikan (non-refundable), kecuali acara dibatalkan oleh penyelenggara.</li>
                <li>E-ticket akan dikirimkan ke email atau dapat dilihat pada menu 'Tiket Saya' di aplikasi.</li>
                <li>Satu akun memiliki batas maksimal pembelian tiket sesuai ketentuan masing-masing event.</li>
            </ul>

            <h5 class="fw-bold text-dark mt-4">3. Penggunaan Tiket</h5>
            <ul>
                <li>E-ticket wajib ditunjukkan saat memasuki area acara.</li>
                <li>Penyelenggara berhak menolak pengunjung yang membawa tiket palsu atau duplikat.</li>
                <li>Dilarang menjual kembali tiket di luar platform resmi dengan harga yang tidak wajar.</li>
            </ul>

            <h5 class="fw-bold text-dark mt-4">4. Perubahan Kebijakan</h5>
            <p>Five Fest berhak mengubah syarat dan ketentuan ini sewaktu-waktu tanpa pemberitahuan sebelumnya. Pengguna diharapkan memeriksa halaman ini secara berkala.</p>
        </div>
        
        <div class="text-center mt-5">
            <a href="{{ route('home') }}" class="btn btn-primary px-4 py-2 rounded-pill">Kembali ke Beranda</a>
        </div>
    </div>
</div>
@endsection
