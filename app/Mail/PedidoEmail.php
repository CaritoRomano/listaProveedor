<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PedidoEmail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var
     */
        
    public $idPedido;   /*SE PASA DIRECTO A LA VISTA*/
    public $nombreUsuario;
    public $observaciones;
    private $url;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($idPedido, $nombreUsuario, $url, $observaciones)
    {
        $this->idPedido = $idPedido;
        $this->nombreUsuario = $nombreUsuario;
        $this->observaciones = $observaciones;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.pedido')
                ->subject('Mensaje de Pedido')
                ->attach($this->url);
    }
}
