<?php

namespace App\Mail\Common;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    protected $message;

    public function __construct($subject, $message)
    {
        $this->subject = $subject;
        $this->message = $message;
    }


    public function build(): TestMail
    {
        return $this->subject($this->subject)
            ->text('notification.mail.template', ['template' => $this->message]);
    }
    }
