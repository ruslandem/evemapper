<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
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
    public string $captchaScore;

    public function __construct(array $input)
    {
        $this->name = $input['name'];
        $this->email = $input['email'];
        $this->body = nl2br($input['message']);
        $this->captchaScore = $input['captchaScore'] ?? null;
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
