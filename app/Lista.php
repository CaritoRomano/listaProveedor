<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lista extends Model
{
    protected $table = "lista";

    protected $filllable = ['codProveedor', 'codArticulo', 'descripcion', 'marca', 'rubro', 'porcIva', 'precio'];
   // protected $guarded = ['id'];
}
