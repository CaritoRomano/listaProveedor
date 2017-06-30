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
    private $url;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($idPedido, $nombreUsuario, $url)
    {
        $this->idPedido = $idPedido;
        $this->nombreUsuario = $nombreUsuario;
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
