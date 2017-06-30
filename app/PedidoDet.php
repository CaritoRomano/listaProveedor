<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PedidoDet extends Model
{
	protected $table = "pedidoDet";

    protected $fillable = [ 'id', 'idEnc', 'codFabrica', 'codArticulo', 'cant', 'precio'];

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
        $query->where([['idPedido', '=', "$idPedido"], ['id', '=', "$idDetalle"]])->get();
    }

           

}


