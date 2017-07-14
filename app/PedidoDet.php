<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PedidoDet extends Model
{
	protected $table = "pedidoDet";
  
    protected $fillable = ['idDetalle', 'idPedido', 'codFabrica', 'codArticulo', 'cant', 'cantRecibida'];

    protected $guarded = ['id'];

    public function scopeArticulosPedidos($query, $idPedido)
    {
   		$query->where('idPedido', '=', "$idPedido")->get();
    }

    public function scopeCodigos($query, $codFabrica, $codArticulo)
    { 
    	if(($codArticulo != "") && ($codFabrica != "")) { 
    		$query->where([['codArticulo', '=', "$codArticulo"], ['codFabrica', '=', "$codFabrica"]])->get();
    	}
    }

    public function scopeId($query, $idPedido, $idDetalle)
    {
        $query->where([['idPedido', '=', "$idPedido"], ['idDetalle', '=', "$idDetalle"]])->get();
    }

    public function scopeArticulosFaltantes($query, $idPedido)
    {
        $query->where('idPedido', '=', "$idPedido")
            ->whereRaw('pedidoDet.cant > pedidoDet.cantRecibida')->get();
    }

    public function scopeExisteArticulo($query, $idPedido, $codFabrica, $codArticulo)
    {
        $query->where([['idPedido', '=', "$idPedido"], ['codFabrica', '=', "$codFabrica"], ['codArticulo', '=', "$codArticulo"]])->get();
    }
           

}


