<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $vendor;
    public $token;

    public function __construct($vendor, $token)
    {
        $this->vendor = $vendor;
        $this->token = $token;
    }

    public function build()
    {
        $verificationUrl = route('verify.email', ['vendor' => $this->vendor->id, 'token' => $this->token]);

        return $this->view('emails.verification')
            ->with([
                'verificationUrl' => $verificationUrl,
            ]);
    }
}
