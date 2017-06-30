<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PedidoEnc extends Model
{
	protected $table = "pedidoEnc";

    protected $fillable = [ 'idUsuario', 'nroPedido', 'fechaEnvio', 'estado', 'cantArticulos', 'totalAPagar'];

    protected $guarded = ['id'];

}
