<?php

namespace App\Mail;

use App\Models\PreRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdmissionApprovedRegistrationLink extends Mailable
{
    use Queueable, SerializesModels;

    public PreRegistration $pre;
    public string $registerUrl;

    public function __construct(PreRegistration $pre, string $registerUrl)
    {
        $this->pre = $pre;
        $this->registerUrl = $registerUrl;
    }

    public function build(): self
    {
        return $this->subject('Félicitations ! Créez votre compte EVC')
            ->replyTo(config('mail.from.address'), config('mail.from.name'))
            ->view('emails.admission_approved_registration_link')
            ->with([
                'pre' => $this->pre,
                'registerUrl' => $this->registerUrl,
            ]);
    }
}
