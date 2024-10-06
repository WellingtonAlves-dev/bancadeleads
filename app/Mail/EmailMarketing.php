<?php 

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailMarketing extends Mailable
{
    use Queueable, SerializesModels;

    public $subject; // Adicione este campo
    public $htmlContent;

    public function __construct($subject, $htmlContent)
    {
        $this->subject = $subject;
        $this->htmlContent = $htmlContent;
    }

    public function build()
    {
        return $this->subject($this->subject)
                    ->view('mail.template');
    }
}
