<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CompanyMail extends Mailable
{
    use Queueable, SerializesModels;
    public $booking;
    public $companyId;
    public $pdfDetails;
    
    /**
     * Create a new message instance.
     */
    public function __construct($booking, $companyId, $pdfDetails = null)
    {
        $this->booking = $booking;
        $this->companyId = $companyId;
        $this->pdfDetails = $pdfDetails;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Company Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.company',
            with: [
                'booking' => $this->booking,
                'companyId' => $this->companyId,
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
        $attachments = [];

        if ($this->pdfDetails && isset($this->pdfDetails['pdf_contents']) && isset($this->pdfDetails['filenames'])) {
            foreach ($this->pdfDetails['pdf_contents'] as $index => $pdfContent) {
                $attachments[] = Attachment::fromData(fn() => $pdfContent, $this->pdfDetails['filenames'][$index])
                    ->withMime('application/pdf');
            }
        }

        return $attachments;
    }
}
