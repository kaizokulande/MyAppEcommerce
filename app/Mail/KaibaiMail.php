<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KaibaiMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $subject;
    public $firstname;
    public $lastname;
    public $code;
    public function __construct($subject,$firstname,$lastname,$code)
    {
        $this->subject      = $subject;
        $this->firstname    = $firstname;
        $this->lastname     = $lastname;
        $this->code         = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('kaibaienterprise@kaibai.jp')->view('email.email_register');
    }
}
