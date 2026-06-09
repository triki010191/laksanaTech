<?php

namespace App\Mail;

use App\Models\ContactLead;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewContactLeadMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(public ContactLead $lead) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[LaksanaTech] Lead Baru: '.($this->lead->subject ?? 'Kontak Website'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.contact-lead',
        );
    }
}
