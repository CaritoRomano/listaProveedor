<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvitadoController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
        //vista articulos invitados    
        return view('invitado.tablaListaArticulos', ['detallePedido' => false, 'subtitulo' => '']);
    }
}