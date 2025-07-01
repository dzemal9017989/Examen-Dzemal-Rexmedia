<?php

namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserInvitation extends Mailable
{
    use Queueable, SerializesModels;
    public $invitation;
    public $acceptUrl;
    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
        $this->acceptUrl = url("/uitnodiging/{$invitation->token}");
    }
    public function build()
    {
        return $this->subject('Uitnodiging voor Suriname App')->view('emails.invitation');
    }
}
