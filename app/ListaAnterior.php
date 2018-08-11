<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListaAnterior extends Model
{
    protected $table = "listaAnterior";
    protected $primaryKey = 'codArticulo'; //ver error con arreglo(surge en Datatables lista para elegir articulos cliente)
    public $incrementing = false;

    protected $guarded = ['id'];

    protected $filllable = ['codFabrica', 'codArticulo', 'descripcion', 'rubro', 'fabrica', 'porcIva', 'precio'];

}
