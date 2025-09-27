<?php

namespace App\Mail;

use App\Models\DesignProject;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DesignProjectDeleted extends Mailable
{
    use Queueable, SerializesModels;

    public $designProject;

    /**
     * Create a new message instance.
     *
     * @param DesignProject $designProject
     */
    public function __construct(DesignProject $designProject)
    {
        $this->designProject = $designProject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Votre projet design a été supprimé - EVC Formation')
                    ->view('emails.design-project-deleted')
                    ->with([
                        'studentName' => $this->designProject->user->first_name . ' ' . $this->designProject->user->last_name,
                        'projectTitle' => $this->designProject->title,
                        'projectType' => $this->designProject->getProjectTypeLabel(),
                        'deletedAt' => now()->format('d/m/Y à H:i'),
                    ]);
    }
}
