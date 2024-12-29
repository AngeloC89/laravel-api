<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class NewContact extends Mailable
{
    use Queueable, SerializesModels;


    public $name;
    public $email;
    public $message;


    /**
     * Create a new message instance.
     */
    public function __construct($name, $email, $message = '')
    {

        $this->name = $name;
        $this->email = $email;
        $this->message = $message;

    }


    /**
     * Restituisce il contenuto dell'email come stringa HTML.
     */
    public function renderHtml(): string
    {
        return view('mails.new-contact', [
            'name' => $this->name,
            'email' => $this->email,
            'message' => $this->message,
        ])->render();
    }
}
