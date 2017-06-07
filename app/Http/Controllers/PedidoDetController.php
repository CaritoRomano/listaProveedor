<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Response;
use App\PedidoEnc;
use App\PedidoDet;
use App\Lista;
use Yajra\Datatables\Datatables;

class PedidoDetController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->hasRole('Cliente')) { 
            //pido el precio desde aca por si codProveedor y codArticulo fueron cambiados en el form (campos ocultos)
            $articulo = Lista::codigos($request->codProveedor, $request->codArticulo)->get();
            if(sizeof($articulo) == 0){ // no existe el articulo
                //cambiar return
                $viewMensinCorrecto = view('mensajes.incorrecto', ['msj' => 'El articulo ' . $request->codProveedor . ' - ' . $request->codArticulo . ' no existe.']); 
                $viewMensinCorrectoRender = $viewMensinCorrecto->renderSections();
                return Response::json(['mensaje' => $viewMensinCorrectoRender['mensajeinCorrecto']]);
            }

            $idDetalle = (PedidoDet::where('idPedido', '=', $request->idPedido)->max('id'))+ 1;  
            $detalle = new PedidoDet();

            $detalle->id = $idDetalle;
            $detalle->idPedido = $request->idPedido;
            $detalle->codProveedor = $request->codProveedor;
            $detalle->codArticulo = $request->codArticulo;
            $detalle->cant = $request->cant;
            $detalle->precio = $articulo[0]->precio;
            $detalle->save();
            //actualizo los datos del enc
            $pedido = PedidoEnc::find($request->idPedido);
            //ver de calcularlo dinamicamente
            $pedido->cantArticulos = $pedido->cantArticulos + $request->cant;
            $pedido->totalAPagar = $pedido->totalAPagar + ($articulo[0]->precio * $request->cant);
            $pedido->save();

            //cambiar return
            $viewMensCorrecto = view('mensajes.correcto', ['msj' => 'El articulo ' . $request->descrip . ' se ha agregado correctamente.']); 
            $viewMensCorrectoRender = $viewMensCorrecto->renderSections();
            $viewDatosPedido = view('cliente.tablaListaArticulos', ['articulosLista' => [], 'pedido' => $pedido, 'detallePedido' => false]); //articulosLista no se actualiza, lo mando vacio para que no de error de inexistencia
            $viewDatosPedidoRender = $viewDatosPedido->renderSections();
            return Response::json(['mensaje' => $viewMensCorrectoRender['mensajeCorrecto'], 'datosPedido' => $viewDatosPedidoRender['datosPedido']]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Auth::user()->hasRole('Cliente')) {
            $pedido = PedidoEnc::find($id);
            $articulosPedidos = PedidoDet::where('idPedido', '=', $id)->orderBy('codArticulo', 'DESC')->paginate(50);
        return view('cliente.tablaListaArticulosPedidos', ['articulosPedidos' => $articulosPedidos, 'pedido' => $pedido, 'detallePedido' => true]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { 
        if (Auth::user()->hasRole('Cliente')) { 
            $detalle = (PedidoDet::Codigos($request->codProveedor, $request->codArticulo)->get());  
            if(sizeof($detalle) == 0){ // no existe el articulo
                //cambiar return
                $viewMensinCorrecto = view('mensajes.incorrecto', ['msj' => 'El articulo ' . $request->codProveedor . ' - ' . $request->codArticulo . ' no existe.']); 
                $viewMensinCorrectoRender = $viewMensinCorrecto->renderSections();
                return Response::json(['mensaje' => $viewMensinCorrectoRender['mensajeinCorrecto']]);
            }    
            
            $cantAnterior = $detalle[0]->cant;
            $totalAnterior = $detalle[0]->cant * $detalle[0]->precio;
            $detalle[0]->cant = $request->cant;
            $detalle[0]->save();
            //actualizo los datos del enc
            $pedido = PedidoEnc::find($request->idPedido);
            //ver de calcularlo dinamicamente
            $pedido->cantArticulos = $pedido->cantArticulos - $cantAnterior + $request->cant;
            $pedido->totalAPagar = $pedido->totalAPagar - $totalAnterior + ($detalle[0]->precio * $request->cant);
            $pedido->save();

            //cambiar return
            $viewMensCorrecto = view('mensajes.correcto', ['msj' => 'El articulo ' . $request->descrip . ' se ha modificado correctamente.']); 
            $viewMensCorrectoRender = $viewMensCorrecto->renderSections();
            $viewDatosPedido = view('cliente.tablaListaArticulos', ['articulosLista' => [], 'pedido' => $pedido, 'detallePedido' => false]); //articulosLista no se actualiza, lo mando vacio para que no de error de inexistencia
            $viewDatosPedidoRender = $viewDatosPedido->renderSections();
            return Response::json(['mensaje' => $viewMensCorrectoRender['mensajeCorrecto'], 'datosPedido' => $viewDatosPedidoRender['datosPedido']]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($idPedido, $idDetalle)
    {   
        if (Auth::user()->hasRole('Cliente')) { 
            $detalle = (PedidoDet::Id($idPedido, $idDetalle)->first());

            $cantAnterior = $detalle->cant;
            $totalAnterior = $detalle->cant * $detalle->precio;            
            $detalle->delete();
            //actualizo los datos del enc
            $pedido = PedidoEnc::find($idPedido);
            //ver de calcularlo dinamicamente
            $pedido->cantArticulos = $pedido->cantArticulos - $cantAnterior;
            $pedido->totalAPagar = $pedido->totalAPagar - $totalAnterior;
            $pedido->save();
            //cambiar return
            $viewMensCorrecto = view('mensajes.correcto', ['msj' => 'El articulo se ha eliminado.']); 
            $viewMensCorrectoRender = $viewMensCorrecto->renderSections();
            $viewDatosPedido = view('cliente.tablaListaArticulos', ['articulosLista' => [], 'pedido' => $pedido, 'detallePedido' => false]); //articulosLista no se actualiza, lo mando vacio para que no de error de inexistencia
            $viewDatosPedidoRender = $viewDatosPedido->renderSections();
            return Response::json(['mensaje' => $viewMensCorrectoRender['mensajeCorrecto'], 'datosPedido' => $viewDatosPedidoRender['datosPedido']]);
        }
    }
}
