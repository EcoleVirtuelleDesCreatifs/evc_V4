<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TpValidationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $tp;
    public $status;
    public $rejectionReason;
    public $tpUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($tp, $status, $rejectionReason = null)
    {
        $this->tp = $tp;
        $this->status = $status;
        $this->rejectionReason = $rejectionReason;
        
        // URL vers la page des TP de l'étudiant
        $formationSlug = strtolower(str_replace(' ', '-', $tp->formation ?? 'community-management'));
        $this->tpUrl = url("/evc/compte/{$formationSlug}/todo/index");
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->status === 'validated' 
            ? '✅ Votre TP a été validé - ' . $this->tp->title
            : '📝 Votre TP nécessite des améliorations - ' . $this->tp->title;
            
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.tp-validation-notification',
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
