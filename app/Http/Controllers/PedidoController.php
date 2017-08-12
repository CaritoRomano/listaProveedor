<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Response;
use App\Lista;
use App\PedidoEnc;
use App\PedidoDet;
use DB;
use App\Mail\PedidoEmail;
use Mail;
use Maatwebsite\Excel\Facades\Excel;
use Storage;
use File;

class PedidoController extends Controller
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
        if (Auth::user()->hasRole('Cliente')) { 
            $pedidos = PedidoEnc::where('idUsuario', '=', Auth::id())->orderBy('nroPedido','DESC')->get();
            return view('cliente.misPedidos.index', ['pedidos' => $pedidos, 'idPedido' => '', 'mensajeEnviado' => false]);

        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()  //REVISAR SI CAMBIO POR PedidoDEt-store()
    {
        if (Auth::user()->hasRole('Cliente')) {
            //veo si tiene un pedido abierto
            if(sizeof(PedidoEnc::where([['idUsuario', '=', Auth::id()], ['estado', '=', "Abierto"]])->get()) == 1) { 
                dd('tiene un pedido abierto');
            }else{
                $pedido = new PedidoEnc;
                $pedido->idUsuario = Auth::id();
                //Calculo nroPedido
                $pedido->nroPedido = (PedidoEnc::where('idUsuario', '=', Auth::id())->max('nroPedido')) + 1;
                $pedido->estado = 'Nuevo';
                $pedido->cantEnvios = 0;
                $pedido->save();
                return redirect()->route('pedido.show', ['id' => $pedido->id]);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)  //REVISAR SI CAMBIO POR LISTA()
    {   //vista para pedir articulos    
     /*   if (Auth::user()->hasRole('Cliente')) { 
            $pedido = PedidoEnc::find($id);
            $articulosLista = Lista::descripcion($request->get('descrip'))->orderBy('codArticulo', 'DESC')->paginate(250);
        return view('cliente.tablaListaArticulos', ['articulosLista' => $articulosLista, 'pedido' => $pedido, 'detallePedido' => false, 'subtitulo' => 'Agregar artículos al pedido']);
        }*/
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function lista()
    {
        //vista para pedir articulos    
        if (Auth::user()->hasRole('Cliente')) { 
            $pedido = PedidoEnc::nuevo(Auth::id())->get();

            if(sizeof($pedido) == 0) { //no tiene pedido nuevo
                return view('cliente.tablaListaArticulos', ['pedido' => [], 'detallePedido' => false, 'subtitulo' => 'Agregar artículos al pedido']);
            }else{
                $pedido = $pedido[0];
                $cantArt = PedidoDet::TotalArt($pedido->id); 
                $total = PedidoDet::Totalizar($pedido->id);
                $infoPedido = ['id' => $pedido->id,'nroPedido' => $pedido->nroPedido, 'cantArticulos' => $cantArt, 'totalAPagar' => $total->total];
                return view('cliente.tablaListaArticulos', ['infoPedido' => $infoPedido, 'detallePedido' => false, 'subtitulo' => 'Agregar artículos al pedido']);    
            }
            
        }else{
            return redirect()->route('home');
        }
    }

    public function guardarObservaciones(Request $request)
    {
        //vista para pedir articulos    
        if (Auth::user()->hasRole('Cliente')) { 
            $pedido = PedidoEnc::find($request->idPedido);
            $pedido->observaciones = $request->observaciones;
            $pedido->save();

            return Response::json(['mensaje' => 'Guardado correctamente']);  
        }
    }

    public function enviarPedido($id)
    {  
        if (Auth::user()->hasRole('Cliente')) {
            $nombreArchivo = $id . '_pedido_' . Auth::user()->name;
            $artPedidos = PedidoDet::articulosPedidos($id)->get()->toArray(); 
            $pedido = PedidoEnc::find($id);   
            $archivo = Excel::create($nombreArchivo, function($excel) use ($artPedidos, $pedido) {
     
                $excel->sheet('Pedido', function($sheet) use ($artPedidos, $pedido) {  //sheet name
                    
                    $sheet->fromArray($artPedidos);
                    $sheet->row(sizeof($artPedidos)+2, array(
                        'Observaciones:  ', $pedido->observaciones
                    ));
     
                });
                
            })->store('xls', storage_path('archivos'));

            if ($archivo) {
                $url = storage_path('archivos')."/".$nombreArchivo . '.xls';
                //$url = realpath('storage/archivos/' . $nombreArchivo);
              
                //Mail::to('pruebasmailsweb@gmail.com')
                Mail::to('repuestosgonnetsa@yahoo.com.ar')
                    ->send(new PedidoEmail($id, Auth::user()->name, $url, $pedido->observaciones));

                $pedido->estado = 'Enviado';
                $pedido->primerFechaEnvio = date("Y-m-d H:i:s"); 
                $pedido->ultFechaEnvio = date("Y-m-d H:i:s"); 
                $pedido->save();
                    
                //elimino el archivo 
                File::delete($url);

                $pedidos = PedidoEnc::where('idUsuario', '=', Auth::id())->orderBy('nroPedido','DESC')->get();
                return view('cliente.misPedidos.index', ['pedidos' => $pedidos, 'idPedido' => '', 'mensajeEnviado' => true]);

            }else{
                //completar si hay un error al guardar
            }
        }  
    }

    public function reenviarPedido($id)
    {  
        if (Auth::user()->hasRole('Cliente')) {
            $nombreArchivo = $id . '_pedido_' . Auth::user()->name;
            $artFaltantes = PedidoDet::articulosFaltantes($id)->get()->toArray(); 
            $pedido = PedidoEnc::find($id);
            $archivo = Excel::create($nombreArchivo, function($excel) use ($artFaltantes, $pedido) {
     
                $excel->sheet('Pedido', function($sheet) use ($artFaltantes, $pedido) {  //sheet name
                    
                    $sheet->fromArray($artFaltantes);
                    $sheet->row(sizeof($artFaltantes)+2, array(
                        'Observaciones:  ', $pedido->observaciones
                    ));
                });
                
            })->store('xls', storage_path('archivos'));

            if ($archivo) {
                $url = storage_path('archivos')."/".$nombreArchivo . '.xls';
                //$url = realpath('storage/archivos/' . $nombreArchivo);
              
                //Mail::to('pruebasmailsweb@gmail.com')
                Mail::to('repuestosgonnetsa@yahoo.com.ar')
                    ->send(new PedidoEmail($id, Auth::user()->name, $url, $pedido->observaciones));

                $pedido->estado = 'Reenviado';
                $pedido->ultFechaEnvio = date("Y-m-d H:i:s"); 
                $pedido->cantEnvios = $pedido->cantEnvios + 1; 
                $pedido->save();
                    
                //elimino el archivo 
                File::delete($url);
                    
                $pedidos = PedidoEnc::where('idUsuario', '=', Auth::id())->orderBy('nroPedido','DESC')->get();
                return view('cliente.misPedidos.index', ['pedidos' => $pedidos, 'idPedido' => '', 'mensajeEnviado' => true]);

            }else{
                //completar si hay un error al guardar
            }
        }  
    }


    public function recibir($id)
    { 
        if (Auth::user()->hasRole('Cliente')) {
            //veo si el pedido abierto
            $pedido = PedidoEnc::find($id);          
            if (($pedido->estado == 'Enviado') || ($pedido->estado == 'Reenviado')){
                return view('cliente.misPedidos.recibir', ['pedido' => $pedido, 'detallePedido' => false, 'subtitulo' => 'Recibir artículos']);
            }
        }
    }

    public function recibirCant()
    { 
        if (Auth::user()->hasRole('Cliente')) {
            //veo si el pedido abierto 
            $pedido = PedidoEnc::where('id', '=', $_POST['idPedido'])->first();
            if (($pedido->estado == 'Enviado') || ($pedido->estado == 'Reenviado')){
                $pedidoDet = PedidoDet::id($_POST['idPedido'], $_POST['idDetalle'])->get();
                $pedidoDet[0]->cantRecibida = $pedidoDet[0]->cantRecibida + $_POST['cantRecibida'];
                $pedidoDet[0]->save();  
                $cantFaltante = $pedidoDet[0]->cant - $pedidoDet[0]->cantRecibida;       
                //si ya se recibieron todos, lo Finalizo
                if(sizeof(PedidoDet::articulosFaltantes($_POST['idPedido'])->get()) == 0 ) {  
                    return Response::json(['finalizado' => 1, 'cantFaltante' => 0]);
                }else{
                    return Response::json(['finalizado' => 0, 'cantFaltante' => $cantFaltante]);   
                }
            }
        }
    }

    public function cerrarPedido($id)
    { 
        if (Auth::user()->hasRole('Cliente')) {
            //veo si el pedido abierto
            $pedido = PedidoEnc::where('id', '=', $id)->first();
            if ( $pedido->estado == 'Nunca entro por aca' ){
                    return Response::json(['muestroModal' => 0]);
            }else{
                //
                
            }
        }
    }
  
    public function anularPedido($id)
    {
        $pedido = PedidoEnc::find($id);
        if(Auth::user()->id == $pedido->idUsuario){     
            $pedido->estado = 'Anulado';
            $pedido->save();
                        
            $pedidos = PedidoEnc::where('idUsuario', '=', Auth::id())->orderBy('nroPedido','DESC')->get();
            return redirect()->route('pedido.index');
        }
    }

    public function finalizarPedido($id)
    {
        $pedido = PedidoEnc::find($id);
        if(Auth::user()->id == $pedido->idUsuario){     
            $pedido->estado = 'Finalizado';
            $pedido->save();
                        
            $pedidos = PedidoEnc::where('idUsuario', '=', Auth::id())->orderBy('nroPedido','DESC')->get();
            return redirect()->route('pedido.index');
           /* return view('cliente.misPedidos.index', ['pedidos' => $pedidos, 'idPedido' => '', 'mensajeEnviado' => false]);  */
        }
    }
}
