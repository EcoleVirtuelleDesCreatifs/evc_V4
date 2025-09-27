<?php

namespace App\Mail;

use App\Models\DesignProject;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DesignProjectValidated extends Mailable
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
        return $this->subject('Votre projet design a été validé - EVC Formation')
                    ->view('emails.design-project-validated')
                    ->with([
                        'studentName' => $this->designProject->user->first_name . ' ' . $this->designProject->user->last_name,
                        'projectTitle' => $this->designProject->title,
                        'projectType' => $this->designProject->project_type_label, // Utilisation de l'accesseur Laravel
                        'validatedAt' => now()->format('d/m/Y à H:i'),
                    ]);
    }
}
