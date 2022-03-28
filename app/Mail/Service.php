<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Service extends Mailable
{
    use Queueable, SerializesModels;

    public $url;
    public $user;
    public $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($url, $user, $email)
    {
        $this->url = $url;
        $this->user = $user;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $from = 'service@stackmemento.com';
        $name = 'Stackmemento\'s dev';
        $subject = 'Stackmemento - sign up validation';
        return $this
                ->to($this->email)
                ->subject($subject)
                ->from($from, $name)
                ->view('emails.subscription');
    }
}
