Halo {{ $booking->user->name }},

Terima kasih atas pembelian tiket Anda!

Kode Booking  : {{ $booking->booking_code }}
Event         : {{ $booking->event->title }}
Tanggal       : {{ \Carbon\Carbon::parse($booking->event->date)->format('l, d F Y') }}
Waktu         : {{ \Carbon\Carbon::parse($booking->event->time)->format('H:i') }} WIB
Venue         : {{ $booking->event->venue }}
Kategori      : {{ $booking->ticket_category->name ?? '-' }}
Jumlah Tiket  : {{ $booking->quantity }}
Total Bayar   : Rp {{ number_format($booking->total_price, 0, ',', '.') }}

Detail Tiket:
@foreach($booking->tickets as $index => $ticket)
{{ $index + 1 }}. Kode: {{ $ticket->ticket_code }} @if($ticket->seat_number)| No. Tiket: {{ $ticket->seat_number }}@endif

@endforeach

Tunjukkan kode tiket di atas saat masuk venue.

Terima kasih,
Tim FIVE FEST
