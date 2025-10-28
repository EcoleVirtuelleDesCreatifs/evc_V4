<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TpAssignmentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $tpTitle;
    public $tpDescription;
    public $deadline;
    public $formation;
    public $filesCount;
    public $tpUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($student, $tpTitle, $tpDescription, $deadline, $formation, $filesCount = 0)
    {
        $this->student = $student;
        $this->tpTitle = $tpTitle;
        $this->tpDescription = $tpDescription;
        $this->deadline = $deadline;
        $this->formation = $formation;
        $this->filesCount = $filesCount;
        
        // URL vers la page des TP de l'étudiant
        $this->tpUrl = url('/evc/compte/' . strtolower(str_replace(' ', '-', $formation)) . '/tp/index');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📚 Nouveau TP assigné : ' . $this->tpTitle,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.tp-assignment-notification',
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
