<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/


Route::get('/', ['uses' => 'Auth\LoginController@showLoginForm', 'as' => 'login'
]);

Auth::routes();

Route::get('/home', ['uses' => 'HomeController@index', 'as' => 'home']);
Route::post('/actualizarLista', ['uses' => 'HomeController@actualizarLista', 'as' => 'actualizarLista']);

Route::post('/home/tabla', function(){  //datatable todos articulos cliente
	return Datatables::eloquent(App\Lista::query())->make(true);
});

Route::post('/home/tablaPedidos', function(){ //datatable articulos pedidos 
	return Datatables::queryBuilder(DB::table('pedidoDet')
            ->join('lista', [['pedidoDet.codArticulo', '=', "lista.codArticulo"], ['pedidoDet.codFabrica', '=', "lista.codFabrica"]])
            ->select('pedidoDet.*', 'lista.descripcion as descripcion', 'lista.fabrica as fabrica', DB::raw('(pedidoDet.cant*pedidoDet.precio) AS importe')) 
            ->where('idPedido', '=', $_POST['id'])  
    		)->make(true);
	//return Datatables::eloquent(App\PedidoDet::ArticulosPedidos($_POST['id']))->make(true);
});

Route::post('/home/tablaCambioPrecios', function(){ //datatable articulos pedidos con cambio de precios
	return	Datatables::queryBuilder(DB::table('pedidoDet')
	    ->join('lista', [['pedidoDet.codArticulo', '=', "lista.codArticulo"], ['pedidoDet.codFabrica', '=', "lista.codFabrica"]])
	    ->select('pedidoDet.*', 'lista.descripcion as descripcion', 'lista.fabrica as fabrica', 'lista.precio as precioLista')
	    ->where('idPedido', '=', $_POST['id'])
	    ->whereRaw('lista.precio <> pedidoDet.precio')
	    )->make(true); 
});



//pedido Enc
Route::post('pedido/cerrarPedido/{idPedido}', ['uses' =>'PedidoController@cerrarPedido', 'as' => 'pedido.cerrarPedido']);
Route::get('pedido/anularPedido/{idPedido}', ['uses' =>'PedidoController@anularPedido', 'as' => 'pedido.anularPedido']);
Route::resource('pedido', 'PedidoController');
//pedido Det
Route::post('/pedidoDet/{idPedido}', ['uses' =>'PedidoDetController@store', 'as' => 'pedidoDet.store']);
Route::put('/pedidoDet/{idPedido}', ['uses' =>'PedidoDetController@update', 'as' => 'pedidoDet.update']);
Route::delete('/eliminarPedido/{idPedido}/{idDtalle}', ['uses' =>'PedidoDetController@destroy', 'as' => 'pedidoDet.destroy']);
Route::get('/detalle/{idPedido}', ['uses' =>'PedidoDetController@show', 'as' => 'pedidoDet.show']);
Route::get('/detalle/cambioPrecios/{idPedido}', ['uses' =>'PedidoDetController@cambioPrecios', 'as' => 'pedidoDet.cambioPrecios']);

