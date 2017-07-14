<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NuevoUsuarioEmail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var
     */
        
    public $usuario;   /*SE PASA DIRECTO A LA VISTA*/
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.nuevoUsuario')
                ->subject('Alta de usuario Repuestos Gonnet');
    }
}
