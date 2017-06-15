<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lista extends Model
{
    protected $table = "lista";
    protected $primaryKey = 'codArticulo'; //ver error con arreglo(surge en Datatables lista para elegir articulos cliente)
    public $incrementing = false;

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
}
