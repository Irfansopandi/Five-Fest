@extends('v_layouts.app')

@section('title', 'Selesaikan Pembayaran')

@section('content')

<style>
    nav {
        display: none !important;
    }

    .payment-container {
        background: #f8fafc;
        min-height: 100vh;
        padding-top: 60px;
        padding-bottom: 80px;
    }

    .payment-card {
        background: white;
        border-radius: 30px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.05);
        border: none;
        overflow: hidden;
    }

    .status-badge {
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-pay-now {
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        color: white;
        border: none;
        padding: 18px 30px;
        border-radius: 50px;
        font-weight: 800;
        width: 100%;
        transition: 0.3s;
        box-shadow: 0 10px 20px rgba(139, 92, 246, 0.3);
    }

    .btn-pay-now:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(139, 92, 246, 0.4);
        color: white;
    }

    .ticket-summary-box {
        background: #f1f5f9;
        border-radius: 20px;
        padding: 25px;
    }

    .timer-badge {
        font-size: 0.9rem;
        border: 1px solid rgba(217, 119, 6, 0.2);
    }

    @media (max-width: 768px) {
        .payment-container {
            padding-top: 40px !important;
            padding-bottom: 40px !important;
        }
        .payment-card {
            border-radius: 20px !important;
        }
        .payment-card .card-body {
            padding: 24px !important;
        }
        .payment-card h2 {
            font-size: 1.8rem !important;
        }
    }
</style>

<section class="payment-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6" data-aos="zoom-in">
                
                <div class="payment-card">
                    <div class="card-body p-5">
                        
                        <div class="text-center mb-5">
                            <div class="timer-badge mb-4 d-inline-block bg-warning bg-opacity-10 text-warning px-4 py-2 rounded-pill fw-bold">
                                <i class="bi bi-clock-history me-2"></i> 
                                Selesaikan Pembayaran: <span id="payment-timer" class="fw-bold">05:00</span>
                            </div>
                            <div class="mb-3">
                                <span class="status-badge bg-warning bg-opacity-10 text-warning">Menunggu Pembayaran</span>
                            </div>
                            <h2 class="fw-black mb-2">Checkout</h2>
                            <p class="text-muted">ID Pesanan: <span class="fw-bold text-dark">#{{ $booking->booking_code }}</span></p>
                        </div>

                        <div class="ticket-summary-box mb-4">
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <div>
                                    <h5 class="fw-bold mb-1 text-dark">{{ $booking->event->title }}</h5>
                                    <div class="text-muted small">
                                        <i class="bi bi-geo-alt me-1"></i> {{ $booking->event->venue }}
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-primary rounded-pill px-3">{{ $booking->ticket_category->name }}</span>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="d-flex justify-content-between small">
                                    <span class="text-muted">Harga Tiket</span>
                                    <span class="fw-bold text-dark">Rp{{ number_format($booking->ticket_category->price, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between small">
                                    <span class="text-muted">Pajak & Biaya Layanan</span>
                                    <span class="fw-bold text-dark">Rp{{ number_format($booking->total_price - $booking->ticket_category->price, 0, ',', '.') }}</span>
                                </div>
                                <hr class="my-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-dark">Total Bayar</span>
                                    <h3 class="fw-black text-primary mb-0">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-light border-0 rounded-4 p-3 mb-5 small text-muted">
                            <i class="bi bi-shield-lock-fill text-success me-2"></i>
                            Pembayaran Anda aman dan terenkripsi melalui Midtrans Secure Gateway.
                        </div>

                        <button id="pay-button" class="btn-pay-now">
                            <i class="bi bi-credit-card-2-front me-2"></i> BAYAR SEKARANG
                        </button>


                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

{{-- MIDTRANS SNAP JS --}}
<script src="https://app.{{ config('midtrans.is_production') ? '' : 'sandbox.' }}midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
    const payButton = document.getElementById('pay-button');
    payButton.addEventListener('click', function () {
        // Trigger snap popup. @see https://docs.midtrans.com/en/snap/integration-guide?guide_step=2-show-pay-button-and-token-to-customer
        window.snap.pay('{{ $booking->snap_token }}', {
            onSuccess: function(result){
                window.location.href = "{{ route('booking.success', $booking->id) }}";
            },
            onPending: function(result){
                Swal.fire({
                    icon: 'info',
                    title: 'Menunggu Pembayaran',
                    text: 'Silakan selesaikan pembayaran Anda sesuai instruksi.',
                    confirmButtonColor: '#8b5cf6'
                });
            },
            onError: function(result){
                Swal.fire({
                    icon: 'error',
                    title: 'Pembayaran Gagal',
                    text: 'Terjadi kesalahan saat memproses pembayaran Anda.',
                    confirmButtonColor: '#ef4444'
                });
            },
            onClose: function(){
                Swal.fire({
                    icon: 'info',
                    title: 'Menunggu Pembayaran',
                    text: 'Silahkan selesaikan pembayaran anda sesuai instruksi.',
                    confirmButtonColor: '#8b5cf6'
                });
            }
        });
    });

    // Payment Timer Logic
    function startPaymentTimer(duration, display) {
        var timer = duration, minutes, seconds;
        var interval = setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;
            display.textContent = minutes + ":" + seconds;
            if (--timer < 0) {
                clearInterval(interval);

                // tutup pop up midtrans jika masih terbuka
                try {
                    window.snap.hide();
                } catch (e) {}
                
                // Call the expire route to update database status and restore quota
                fetch("{{ route('booking.expire', $booking->id) }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    }
                }).then(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Waktu Pembayaran Habis',
                        text: 'Sesi pembayaran Anda telah berakhir. Anda akan dialihkan ke Beranda.',
                        confirmButtonColor: '#8b5cf6',
                        timer: 2500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = "{{ url('/') }}";
                    });
                }).catch(err => {
                    console.error("Failed to expire booking: ", err);
                    window.location.href = "{{ url('/') }}";
                });
            }
        }, 1000);
    }

    window.onload = function() {
        startPaymentTimer({{ $secondsRemaining }}, document.getElementById('payment-timer'));
    };
</script>

@endsection