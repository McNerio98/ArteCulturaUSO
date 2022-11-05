<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
class TestEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        //$this->from('ax.minckgmail.com'); Se pasa de forma global
        $this->view('emails.email-test')
                ->subject("Test de correo");
    }    

}
?>