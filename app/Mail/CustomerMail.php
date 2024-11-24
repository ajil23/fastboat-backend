<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class CustomerMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contact;
    protected $emailData;

    /**
     * Create a new message instance.
     */
    public function __construct(Contact $contact, array $emailData)
    {
        $this->contact = $contact;
        $this->emailData = $emailData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Booking Confirmation', // Lebih deskriptif untuk customer
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.customer',
            with: [
                'contact' => $this->contact,
                'ticket_data' => $this->emailData['ticket_data'] ?? [], // Meneruskan data tiket ke view
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        if (empty($this->emailData['pdf_contents'])) {
            return [];
        }

        $attachments = [];
        
        // Iterasi untuk menambahkan semua lampiran PDF
        foreach ($this->emailData['pdf_contents'] as $index => $pdfContent) {
            $attachments[] = Attachment::fromData(
                fn () => $pdfContent,
                $this->emailData['filenames'][$index]
            )->withMime('application/pdf');
        }

        return $attachments;
    }
}