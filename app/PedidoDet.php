<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PedidoDet extends Model
{
	protected $table = "pedidoDet";

    protected $fillable = [ 'id', 'idEnc', 'codProveedor', 'codArticulo', 'cant', 'precio'];

    public function scopeArticulosPedidos($query, $idPedido)
    {
   		$query->where('idPedido', '=', "$idPedido")->get();
    }

    public function scopeCodigos($query, $codProveedor, $codArticulo)
    {
    	if(($codArticulo != "") && ($codProveedor != "")) { 
    		$query->where([['codArticulo', '=', "$codArticulo"], ['codProveedor', '=', "$codProveedor"]])->get();
    	}
    }

    public function scopeId($query, $idPedido, $idDetalle)
    {
        $query->where([['idPedido', '=', "$idPedido"], ['id', '=', "$idDetalle"]])->get();
    }

}


