<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Response;
use App\Lista;
use App\PedidoEnc;
use DB;

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
            return view('cliente.misPedidos.preciosDistintos', ['pedidos' => $pedidos, 'artsPrecioDistinto' => [], 'idPedido' => '']);

        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
                $pedido->estado = 'Abierto';
                $pedido->cantArticulos = 0;
                $pedido->totalAPagar = 0;
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
    public function show($id, Request $request)
    {   //vista para pedir articulos    
        if (Auth::user()->hasRole('Cliente')) { 
            $pedido = PedidoEnc::find($id);
            $articulosLista = Lista::descripcion($request->get('descrip'))->orderBy('codArticulo', 'DESC')->paginate(250);
        return view('cliente.tablaListaArticulos', ['articulosLista' => $articulosLista, 'pedido' => $pedido, 'detallePedido' => false]);
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

    public function cerrarPedido($id)
    { 
        if (Auth::user()->hasRole('Cliente')) {
            //veo si el pedido abierto
            $pedido = PedidoEnc::where('id', '=', $id)->first();
            if ( $pedido->estado == 'Abierto' ){
                //articulos cambiodeprecios
                $artsPrecioDistinto = DB::table('pedidoDet')
                    ->join('lista', [['pedidoDet.codArticulo', '=', "lista.codArticulo"], ['pedidoDet.codFabrica', '=', "lista.codFabrica"]])
                    ->select('pedidoDet.*', 'lista.descripcion as descripcion', 'lista.fabrica as fabrica', 'lista.precio as precioLista')
                    ->where('idPedido', '=', $id)
                    ->whereRaw('lista.precio <> pedidoDet.precio')->get(); 
                if(sizeof($artsPrecioDistinto) > 0) { //si hay art que cambiaron el precio
                    $viewPrecioDistinto = view('cliente.misPedidos.preciosDistintos', ['pedidos' => [], 'artsPrecioDistinto' => $artsPrecioDistinto, 'idPedido' => $id]); 
                    $viewPrecioDistintoRender = $viewPrecioDistinto->renderSections();
                    return Response::json(['tabla' => $viewPrecioDistintoRender['tablaPreciosDistintos']]);
                }


                /*$pedido->fechaEnvio = date("Y-m-d H:i:s"); 
                $pedido->estado = 'Cerrado';
                $pedido->save();
                
                $pedidos = PedidoEnc::where('idUsuario', '=', Auth::id())->orderBy('nroPedido','DESC')->get();

                
                return redirect()->route('pedido.index'); */
            }else{
                //el pedido no esta abierto
                
            }
        }
    }

    public function anularPedido($id)
    {
        dd($id);
    }
}
