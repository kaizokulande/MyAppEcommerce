<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PlanMail extends Mailable
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
    public $limitdate;
    public $subsname;
    public function __construct($subject,$firstname,$lastname,$limitdate,$subsname)
    {
        $this->subject      =        $subject ;
        $this->firstname    =      $firstname ;
        $this->lastname     =       $lastname ;
        $this->limitdate    =      $limitdate ;
        $this->subsname     =       $subsname ;
        /* dump($this->firstname);
        dump($this->lastname);
        dump($this->limitdate);
        dump($this->subsname);
        dd($firstname); */
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('kaibaienterprise@kaibai.jp')->view('email.plan_register_mail');
    }
}
