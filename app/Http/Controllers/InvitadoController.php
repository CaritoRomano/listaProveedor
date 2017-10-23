<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lista;
use Excel;
use Auth;
use Response;

class InvitadoController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
        if (Auth::check()) { //esta logueado
            return redirect()->route('home');
        }else{
        //vista articulos invitados    
            return view('invitado.tablaListaArticulos', ['detallePedido' => false, 'subtitulo' => '']);
        }
    }

    
    /*EXPORTAR LISTA COMPLETA A EXCEL*/
    public function exportarListaCompleta(){
        $file= storage_path('archivos')."/RepuestosGonnet.csv";

        $headers = array('Content-Type: application/csv');

        return Response::download($file, 'RepuestosGonnet.csv', $headers);

        /*$return['path'=>'http://'.Request::server('HTTP_HOST').'/export/'.$nombreArchivo.'xls']; 
        */
    }    

    /*FIN EXPORTAR LISTA COMPLETA A EXCEL*/
}