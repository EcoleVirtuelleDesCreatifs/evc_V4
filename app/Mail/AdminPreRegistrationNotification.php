<?php

namespace App\Mail;

use App\Models\PreRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminPreRegistrationNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public PreRegistration $pre;

    public function __construct(PreRegistration $pre)
    {
        $this->pre = $pre;
    }

    public function build(): self
    {
        return $this->subject('Nouvelle candidature reçue')
            ->view('emails.admin_pre_registration_notification')
            ->with(['pre' => $this->pre]);
    }
}
