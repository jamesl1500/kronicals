<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class IncogReply extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        // User info
        $this->name = $data['fullname'];

        // Url
        $this->url = $data['url'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New reply!')->markdown('templates.emails.IncogReply')->with([
            'name' => $this->name,
            'url' => $this->url
        ]);
    }
}
