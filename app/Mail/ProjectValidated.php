<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Project;
use App\Models\User;

class ProjectValidated extends Mailable
{
    use Queueable, SerializesModels;

    public $project;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(Project $project, User $user)
    {
        $this->project = $project;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Votre projet "' . $this->project->title . '" a été validé !',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.project-validated',
            with: [
                'project' => $this->project,
                'user' => $this->user,
                'projectTitle' => $this->project->title,
                'projectDescription' => $this->project->description,
                'validationDate' => now()->format('d/m/Y à H:i'),
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
        return [];
    }
}
