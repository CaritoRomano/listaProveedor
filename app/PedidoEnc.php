<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class PedidoEnc extends Model
{
	protected $table = "pedidoEnc";

    protected $fillable = [ 'idUsuario', 'nroPedido', 'primerFechaEnvio', 'estado', 'ultFechaEnvio', 'cantEnvios', 'observaciones' ];

    protected $guarded = ['id'];

    public function scopeNuevo($query, $idUsuario)
    {
        $query->where([['estado', '=', "Nuevo"], ['idUsuario', '=', "$idUsuario"]])->get();
    }

    public function scopeIdPedidosPendientes($query, $idUsuario, $fechaConFormato)
    {
        $query->select('id')
        		->where('idUsuario', '=', $idUsuario)
                ->where(DB::raw('DATE(ultFechaEnvio)'), '<=', $fechaConFormato)
            	->where(function($query) {
                    $query->where('estado', '=', 'Enviado')
                        ->orWhere('estado', '=', 'Reenviado'); 
                });
    } 

}
