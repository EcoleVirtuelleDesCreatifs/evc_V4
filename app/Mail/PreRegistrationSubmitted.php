<?php

namespace App\Mail;

use App\Models\PreRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PreRegistrationSubmitted extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public PreRegistration $pre;

    /**
     * Create a new message instance.
     */
    public function __construct(PreRegistration $pre)
    {
        $this->pre = $pre;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject("Confirmation de votre candidature à l’EVC")
            ->view('emails.pre_registration_submitted')
            ->with(['pre' => $this->pre]);
    }
}
