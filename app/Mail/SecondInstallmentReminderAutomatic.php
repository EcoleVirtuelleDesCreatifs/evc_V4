<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SecondInstallmentReminderAutomatic extends Mailable
{
    use Queueable, SerializesModels;

    public $candidate;
    public $payment;
    public $paymentUrl;
    public $daysRemaining;

    /**
     * Create a new message instance.
     *
     * @param object $candidate
     * @param object $payment
     * @param string $paymentUrl
     * @param int $daysRemaining
     */
    public function __construct($candidate, $payment, $paymentUrl, $daysRemaining = 7)
    {
        $this->candidate = $candidate;
        $this->payment = $payment;
        $this->paymentUrl = $paymentUrl;
        $this->daysRemaining = $daysRemaining;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('⚠️ URGENT : Finalisez votre paiement - Risque de désactivation de compte')
            ->view('emails.second_installment_reminder_auto')
            ->with([
                'candidate' => $this->candidate,
                'payment' => $this->payment,
                'paymentUrl' => $this->paymentUrl,
                'daysRemaining' => $this->daysRemaining,
            ]);
    }
}
