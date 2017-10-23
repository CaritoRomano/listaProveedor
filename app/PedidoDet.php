<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class PedidoDet extends Model
{
	protected $table = "pedidoDet";
  
    protected $fillable = ['idDetalle', 'idPedido', 'codFabrica', 'codArticulo', 'cant', 'cantRecibida'];

    protected $guarded = ['id'];

    public function scopeArticulosPedidos($query, $idPedido)
    {
   		$query->select('codFabrica as Fabrica', 'codArticulo as Articulo', 'cant as Cantidad')
            ->where('idPedido', '=', "$idPedido")->get();
    }

  /*  public function scopeCodigos($query, $codFabrica, $codArticulo)
    { 
    	if(($codArticulo != "") && ($codFabrica != "")) { 
    		$query->where([['codArticulo', '=', "$codArticulo"], ['codFabrica', '=', "$codFabrica"]])->get();
    	}
    }
*/
    public function scopeId($query, $idPedido, $idDetalle)
    {
        $query->where([['idPedido', '=', "$idPedido"], ['idDetalle', '=', "$idDetalle"]])->get();
    }

    public function scopeArticulosFaltantes($query, $idPedido)
    {
        $query->select('codFabrica as Fabrica', 'codArticulo as Articulo', DB::raw('(cant - cantRecibida) as Cantidad'))
            ->where('idPedido', '=', "$idPedido")
            ->whereRaw('pedidoDet.cant > pedidoDet.cantRecibida')->get();
    }

    public function scopeExisteArticulo($query, $idPedido, $codFabrica, $codArticulo)
    {
        $query->where([['idPedido', '=', "$idPedido"], ['codFabrica', '=', "$codFabrica"], ['codArticulo', '=', "$codArticulo"]])->get();
    }

    public function scopeTotalArt($query, $idPedido)
    {
        return $query->where('idPedido', '=', "$idPedido")->sum('cant');
    }

    public function scopeTotalizar($query, $idPedido)
    {
        return $query->join('lista', [['pedidoDet.codArticulo', '=', "lista.codArticulo"], ['pedidoDet.codFabrica', '=', "lista.codFabrica"]])
            ->select(DB::raw('SUM(lista.precio * pedidoDet.cant) AS total'))
            ->where('idPedido', '=', "$idPedido")
            ->first();
    }



}