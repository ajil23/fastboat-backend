<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SupplierMail extends Mailable
{
    use Queueable, SerializesModels;
    public $booking;
    public $companyId;
    public $ticketData;

    /**
     * Create a new message instance.
     */
    public function __construct($booking, $companyId, $ticketData)
    {
        $this->booking = $booking;
        $this->companyId = $companyId;
        $this->ticketData = $ticketData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Supplier Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.supplier',
            with: [
                'booking' => $this->booking,
                'companyId' => $this->companyId,
                'ticketData' => $this->ticketData,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
