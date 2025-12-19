<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SecondInstallmentReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $candidate;
    public $payment;
    public $paymentUrl;

    /**
     * Create a new message instance.
     *
     * @param object $candidate
     * @param object $payment
     * @param string $paymentUrl
     */
    public function __construct($candidate, $payment, string $paymentUrl)
    {
        $this->candidate = $candidate;
        $this->payment = $payment;
        $this->paymentUrl = $paymentUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Finalisez votre inscription - 2ème Tranche - École Virtuelle des Créatifs')
            ->view('emails.second_installment_reminder')
            ->with([
                'candidate' => $this->candidate,
                'payment' => $this->payment,
                'paymentUrl' => $this->paymentUrl,
            ]);
    }
}
