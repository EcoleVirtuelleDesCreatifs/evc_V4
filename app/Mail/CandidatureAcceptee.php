<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\PreRegistration;

class CandidatureAcceptee extends Mailable
{
    use Queueable, SerializesModels;

    public $preRegistration;
    public $paymentUrl;
    public $payment;

    /**
     * Create a new message instance.
     *
     * @param PreRegistration $preRegistration
     * @param string $paymentUrl
     * @param object $payment
     */
    public function __construct(PreRegistration $preRegistration, string $paymentUrl, $payment)
    {
        $this->preRegistration = $preRegistration;
        $this->paymentUrl = $paymentUrl;
        $this->payment = $payment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Félicitations ! Votre candidature a été acceptée - École Virtuelle des Créatifs')
            ->view('emails.candidature_acceptee')
            ->with([
                'pre' => $this->preRegistration,
                'paymentUrl' => $this->paymentUrl,
                'payment' => $this->payment,
            ]);
    }
}
