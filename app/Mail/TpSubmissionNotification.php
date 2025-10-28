<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TpSubmissionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $tpTitle;
    public $tpDescription;
    public $submissionLink;
    public $filesCount;
    public $tpUrl;
    public $formation;

    /**
     * Create a new message instance.
     */
    public function __construct($student, $tpTitle, $tpDescription, $formation, $submissionLink = null, $filesCount = 0)
    {
        $this->student = $student;
        $this->tpTitle = $tpTitle;
        $this->tpDescription = $tpDescription;
        $this->formation = $formation;
        $this->submissionLink = $submissionLink;
        $this->filesCount = $filesCount;
        
        // URL vers la page de gestion des TP dans l'admin
        $this->tpUrl = url('/evc/app/admin/travaux');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎓 Nouveau TP soumis : ' . $this->tpTitle,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.tp-submission-notification',
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
