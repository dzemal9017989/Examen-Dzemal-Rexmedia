<?php

namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserInvitation extends Mailable
{
    use Queueable, SerializesModels;

    // Two properties are setting the foundation for the email
    public $invitation;
    public $acceptUrl;

    // This constructor initializes the invitation and the URL for accepting the invitation
    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
        $this->acceptUrl = "http://localhost:5173/uitnodiging/{$invitation->token}";
    }

    // This method builds the email with a subject and a view
    public function build()
    {
        return $this->subject('Uitnodiging voor Suriname App')->view('emails.invitation');
    }
}
