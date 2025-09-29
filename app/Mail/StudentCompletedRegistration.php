<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentCompletedRegistration extends Mailable
{
    use Queueable, SerializesModels;

    public object $user;

    public function __construct(object $user)
    {
        $this->user = $user;
    }

    public function build(): self
    {
        return $this->subject('Un étudiant a finalisé la création de son compte')
            ->view('emails.student_completed_registration')
            ->with(['user' => $this->user]);
    }
}
