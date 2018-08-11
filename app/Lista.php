<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lista extends Model
{
    protected $table = "lista";
    protected $primaryKey = 'codArticulo'; //ver error con arreglo(surge en Datatables lista para elegir articulos cliente)
    public $incrementing = false;

    protected $guarded = ['id'];

    protected $filllable = ['codFabrica', 'codArticulo', 'descripcion', 'rubro', 'fabrica', 'porcIva', 'precio'];
   // protected $guarded = ['id'];


    public function scopeCodigos($query, $codFabrica, $codArticulo)
    {
    	if(($codArticulo != "") && ($codFabrica != "")) { 
    		$query->where([['codArticulo', '=', "$codArticulo"], ['codFabrica', '=', "$codFabrica"]])->get();
    	}
    }

    public function scopeDescripcion($query, $descripcion)
    {
    	if(trim($descripcion) != ""){ //trim elimina espacios en blanco
    		$query->where('descripcion', 'like', "%$descripcion%")->get();
    	}
    }

    public function scopeUltActualizacion($query)
    {
        $query->select('created_at as ultActualizacion')->first();
    }

    public function scopeFabricasActualizadas($query)
    {
        $query->join('listaAnterior', [['lista.codFabrica', '=', "listaAnterior.codFabrica"], ['lista.codArticulo', '=', "listaAnterior.codArticulo"]])
            ->select('lista.fabrica')
            ->whereRaw('lista.precio <> listaAnterior.precio')
            ->groupBy('lista.fabrica')->get();
        /*SELECT L.fabrica
        FROM lista L inner join listaAnterior LA on (LA.codFabrica = L.codFabrica and LA.codArticulo = L.codArticulo)
        WHERE L.precio <> LA.precio
        GROUP BY fabrica*/
    }

}