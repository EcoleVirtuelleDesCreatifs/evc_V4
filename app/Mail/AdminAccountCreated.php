<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminAccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $adminName;
    public $adminEmail;
    public $adminPassword;
    public $adminRole;
    public $loginUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $email, $password, $role)
    {
        $this->adminName = $name;
        $this->adminEmail = $email;
        $this->adminPassword = $password;
        $this->adminRole = $role;
        $this->loginUrl = url('/evc/app/admin/login');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Bienvenue - Vos identifiants administrateur EVC')
                    ->view('emails.admin-account-created');
    }
}
