<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserContact extends Mailable
{
    use Queueable, SerializesModels;

    public $maildata;

    public function __construct($maildata)
    {
        $this->maildata = $maildata;
    }

    public function build()
    {
        return $this->subject('Hey Baksana!')
                    ->view('emails.authority_invite');
    }
}
