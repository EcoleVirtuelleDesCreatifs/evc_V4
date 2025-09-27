<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeStudentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $formations;
    public $loginUrl;
    public $confirmationUrl;
    public $temporaryPassword;

    /**
     * Create a new message instance.
     */
    public function __construct($student, $formations, $temporaryPassword = 'password123')
    {
        $this->student = $student;
        $this->formations = $formations;
        $this->temporaryPassword = $temporaryPassword;
        $this->loginUrl = url('/auth/evc/login');
        
        // Générer l'URL de confirmation avec un token unique
        $confirmationToken = base64_encode($student['email'] . '|' . time() . '|' . md5($student['email'] . config('app.key')));
        $this->confirmationUrl = url('/student/confirm-registration/' . $confirmationToken);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bienvenue à l\'École Virtuelle des Créatifs - Vos identifiants de connexion',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome-student',
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
