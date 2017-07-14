<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PedidoEnc extends Model
{
	protected $table = "pedidoEnc";

    protected $fillable = [ 'idUsuario', 'nroPedido', 'primerFechaEnvio', 'estado', 'cantArticulos', 'totalAPagar', 'ultFechaEnvio', 'cantEnvios', 'observaciones' ];

    protected $guarded = ['id'];

    public function scopeNuevo($query)
    {
        $query->where('estado', '=', "Nuevo")->get();
    }

}
