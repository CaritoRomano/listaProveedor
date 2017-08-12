<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Response;
use App\PedidoEnc;
use App\PedidoDet;
use App\Lista;

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
            //pido el precio desde aca por si codFabrica y codArticulo fueron cambiados en el form (campos ocultos)
            $articulo = Lista::codigos($request->codFabrica, $request->codArticulo)->get();
            if(sizeof($articulo) == 0){ // no existe el articulo
                //cambiar return
                $viewMensinCorrecto = view('mensajes.incorrecto', ['msj' => 'El articulo ' . $request->codFabrica . ' - ' . $request->codArticulo . ' no existe.']); 
                $viewMensinCorrectoRender = $viewMensinCorrecto->renderSections();
                return Response::json(['mensaje' => $viewMensinCorrectoRender['mensajeinCorrecto']]);
            }

            //busco pedido Nuevo, sino creo uno nuevo
            $pedido = PedidoEnc::nuevo(Auth::id())->get();  
            
            if(sizeof($pedido) == 0) { //no tiene pedido nuevo
                $pedido = new PedidoEnc;
                $pedido->idUsuario = Auth::id();
                //Calculo nroPedido
                $pedido->nroPedido = (PedidoEnc::where('idUsuario', '=', Auth::id())->max('nroPedido')) + 1;
                $pedido->estado = 'Nuevo';
                $pedido->save();
            }else{
                $pedido = $pedido[0]; 
                //si ya esta cargado el articulo en el pedido
                if (sizeof(PedidoDet::existeArticulo($pedido->id, $request->codFabrica, $request->codArticulo)->get()) > 0){
                    $idPedido = ['id' => $pedido->id];
                    return Response::json(['muestroModal' => 1, 'datosPedido' => $idPedido]);
                }
            }

            $idDetalle = (PedidoDet::where('idPedido', '=', $pedido->id)->max('idDetalle'))+ 1;  
            $detalle = new PedidoDet();

            $detalle->idDetalle = $idDetalle;
            $detalle->idPedido = $pedido->id;
            $detalle->codFabrica = $request->codFabrica;
            $detalle->codArticulo = $request->codArticulo;
            $detalle->cant = $request->cant;
            $detalle->cantRecibida = 0;
            $detalle->save();

            //cambiar return
            $viewMensCorrecto = view('mensajes.correcto', ['msj' => 'El articulo ' . $request->descrip . ' se ha agregado correctamente.']); 
            $viewMensCorrectoRender = $viewMensCorrecto->renderSections();

            $cantArt = PedidoDet::TotalArt($pedido->id); 
            $total = PedidoDet::Totalizar($pedido->id);
            $infoPedido = ['id' => $pedido->id,'nroPedido' => $pedido->nroPedido, 'cantArticulos' => $cantArt, 'totalAPagar' => $total->total];

            $viewDatosPedido = view('cliente.tablaListaArticulos', ['infoPedido' => $infoPedido, 'detallePedido' => false, 'subtitulo' => '']); //articulosLista no se actualiza, lo mando vacio para que no de error de inexistencia

            $viewDatosPedidoRender = $viewDatosPedido->renderSections();
        
            return Response::json(['muestroModal' => 0, 'datosPedido' => $viewDatosPedidoRender['datosPedido']]);
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
            $cantArt =  PedidoDet::TotalArt($id); 
            $total = PedidoDet::Totalizar($id);
            $infoPedido = ['id' => $pedido->id,'nroPedido' => $pedido->nroPedido, 'cantArticulos' => $cantArt, 'totalAPagar' => $total->total, 'observaciones' => $pedido->observaciones];
            return view('cliente.tablaListaArticulosPedidos', ['infoPedido' => $infoPedido, 'detallePedido' => true, 'subtitulo' => 'Artículos del pedido' ]);
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
    public function update(Request $request)
    {  
        if (Auth::user()->hasRole('Cliente')) { 
            $detalle = (PedidoDet::Id($request->idPedido, $request->idDetalle)->get());  
            if(sizeof($detalle) == 0){ // no existe el articulo
                //cambiar return
                $viewMensinCorrecto = view('mensajes.incorrecto', ['msj' => 'El articulo ' . $request->codFabrica . ' - ' . $request->codArticulo . ' no existe.']); 
                $viewMensinCorrectoRender = $viewMensinCorrecto->renderSections();
                return Response::json(['mensaje' => $viewMensinCorrectoRender['mensajeinCorrecto']]);
            }    
            
            $cantAnterior = $detalle[0]->cant;
            $totalAnterior = $detalle[0]->cant * $detalle[0]->precio;
            $detalle[0]->cant = $request->cant;
            $detalle[0]->save();

            $pedido = PedidoEnc::find($request->idPedido);
            $cantArt =  PedidoDet::TotalArt($request->idPedido); 
            $total = PedidoDet::Totalizar($request->idPedido);
            $infoPedido = ['id' => $pedido->id,'nroPedido' => $pedido->nroPedido, 'cantArticulos' => $cantArt, 'totalAPagar' => $total->total, 'observaciones' => $pedido->observaciones];

            //cambiar return
            $viewMensCorrecto = view('mensajes.correcto', ['msj' => 'El articulo ' . '' . ' se ha modificado correctamente.']); 
            $viewMensCorrectoRender = $viewMensCorrecto->renderSections();
            $viewDatosPedido = view('cliente.tablaListaArticulosPedidos', ['infoPedido' => $infoPedido, 'detallePedido' => true, 'subtitulo' => 'Artículos']); //articulosLista no se actualiza, lo mando vacio para que no de error de inexistencia
            $viewDatosPedidoRender = $viewDatosPedido->renderSections();
            return Response::json(['mensaje' => $viewMensCorrectoRender['mensajeCorrecto'], 'datosPedido' => $viewDatosPedidoRender['datosPedido']]); //admin.js submit
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
        $pedido = PedidoEnc::find($idPedido);
        if ((Auth::user()->hasRole('Cliente')) && (Auth::user()->id == $pedido->idUsuario)){   
            $detalle = (PedidoDet::Id($idPedido, $idDetalle)->first()); 
            if(!is_null($detalle)) {   //me fijo si ya no fue eliminado
                $detalle->delete();   
            }
            
            //si es el ultimo articulo elimino el encabezado
            if (sizeof(PedidoDet::ArticulosPedidos($idPedido)->get()) == 0){          
                $pedido->delete();
                return Response::json(['mensaje' => '', 'datosPedido' => [], 'ultimo' => true]);
            }

            //cambiar return
            $viewMensCorrecto = view('mensajes.correcto', ['msj' => 'El articulo se ha eliminado.']); 
            $viewMensCorrectoRender = $viewMensCorrecto->renderSections();

            $cantArt = PedidoDet::TotalArt($pedido->id); 
            $total = PedidoDet::Totalizar($pedido->id);
            $infoPedido = ['id' => $pedido->id,'nroPedido' => $pedido->nroPedido, 'cantArticulos' => $cantArt, 'totalAPagar' => $total->total, 'observaciones' => $pedido->observaciones];

            $viewDatosPedido = view('cliente.tablaListaArticulosPedidos', ['infoPedido' => $infoPedido, 'detallePedido' => true, 'subtitulo' => 'Artículos del pedido']); //articulosLista no se actualiza, lo mando vacio para que no de error de inexistencia
            $viewDatosPedidoRender = $viewDatosPedido->renderSections();

            return Response::json(['mensaje' => $viewMensCorrectoRender['mensajeCorrecto'], 'datosPedido' => $viewDatosPedidoRender['datosPedido'], 'ultimo' => false]);
        }
    }

}
