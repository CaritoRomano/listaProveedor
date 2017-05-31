<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lista extends Model
{
    protected $table = "lista";

    protected $filllable = ['codProveedor', 'codArticulo', 'descripcion', 'marca', 'rubro', 'porcIva', 'precio'];
   // protected $guarded = ['id'];

    public function scopeCodigos($query, $codProveedor, $codArticulo)
    {
    	if(($codArticulo != "") && ($codProveedor != "")) { 
    		$query->where([['codArticulo', '=', "$codArticulo"], ['codProveedor', '=', "$codProveedor"]])->get();
    	}
    }

    public function scopeDescripcion($query, $descripcion)
    {
    	if(trim($descripcion) != ""){ //trim elimina espacios en blanco
    		$query->where('descripcion', 'like', "%$descripcion%")->get();
    	}
    }
}
