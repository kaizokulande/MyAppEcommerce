<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CreationShopMail extends Mailable
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
    public $shop_name;
    public function __construct($subject,$firstname,$lastname,$shop_name)
    {
        $this->subject      = $subject;
        $this->firstname    = $firstname;
        $this->lastname     = $lastname;
        $this->shop_name    = $shop_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('kaibaienterprise@kaibai.jp')->view('email.shop_creation_mail');
    }
}
