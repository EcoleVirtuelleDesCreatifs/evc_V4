<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $notificationType;
    public $data;

    /**
     * Create a new message instance.
     */
    public function __construct($subject, $notificationType, $data = [])
    {
        $this->subject = $subject;
        $this->notificationType = $notificationType;
        $this->data = $data;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->subject)
                    ->view('emails.admin-notification');
    }
}
