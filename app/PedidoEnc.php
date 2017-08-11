<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PedidoEnc extends Model
{
	protected $table = "pedidoEnc";

    protected $fillable = [ 'idUsuario', 'nroPedido', 'primerFechaEnvio', 'estado', 'ultFechaEnvio', 'cantEnvios', 'observaciones' ];

    protected $guarded = ['id'];

    public function scopeNuevo($query, $idUsuario)
    {
        $query->where([['estado', '=', "Nuevo"], ['idUsuario', '=', "$idUsuario"]])->get();
    }

}
