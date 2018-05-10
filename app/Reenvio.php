<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reenvio extends Model
{
    protected $table = "reenvios";

    protected $fillable = ['idUsuario', 'observaciones', 'ultimaFecha'];

    protected $guarded = ['id'];

}
