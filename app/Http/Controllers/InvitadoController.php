<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lista;
use Excel;
use Auth;
use Response;
use Debugbar;

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
            $ultActualizacionLista = Lista::ultActualizacion()->get();
            $fabricasActualizadas = Lista::FabricasActualizadas()->get();
            return view('invitado.tablaListaArticulos', ['detallePedido' => false, 'subtitulo' => '', 'ultActualizacionLista' => $ultActualizacionLista[0], 'fabricasActualizadas' => $fabricasActualizadas]);
        }
    }

    
    /*EXPORTAR LISTA COMPLETA A EXCEL*/
    public function exportarListaCompleta(){
        $file= storage_path('archivos')."/RepuestosGonnet.csv";

        $headers = array('Content-Type: application/csv');

        return Response::download($file, 'RepuestosGonnet.csv', $headers);
    }    
    /*FIN EXPORTAR LISTA COMPLETA A EXCEL*/

    /*EXPORTAR LISTA COMPLETA A DBF*/
    public function exportarListaCompletaDBF(){
        $file= storage_path('archivos')."/RepuestosGonnet.dbf";

        return Response::download($file, 'RepuestosGonnet.dbf');
    }    
    /*FIN EXPORTAR LISTA COMPLETA A DBF*/

}