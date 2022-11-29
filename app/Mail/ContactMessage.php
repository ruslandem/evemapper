<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    public string $name;
    public string $email;
    public string $body;
    public string $date;

    public function __construct(Request $request)
    {
        $this->name = $request->name;
        $this->email = $request->email;
        $this->body = nl2br($request->message);
        $this->date = date("c");
    }

    public function build()
    {
        return $this
            ->subject('Message from EveMapper')
            ->from(Config::get('mail.sender'))
            ->view('emails.contact');
    }
}
