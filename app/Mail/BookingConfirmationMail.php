<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Headers;

class BookingConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    public function __construct($booking)
    {
        // Reload booking dengan relasi yang dibutuhkan
        $this->booking = $booking->load(['tickets', 'event', 'user', 'ticket_category']);
    }

    public function headers(): Headers
    {
        return new Headers(
            text: [
                'Precedence'     => 'transactional',
                'Auto-Submitted' => 'auto-generated',
            ],
        );
    }

    public function build()
    {
        return $this->subject('Tiket FIVE FEST #' . $this->booking->booking_code)
                    ->view('emails.ticket-email')
                    ->text('emails.ticket-email-text');
    }
}