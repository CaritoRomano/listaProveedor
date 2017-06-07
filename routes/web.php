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

Route::post('/home/tablaPedidos', function(){ //datatable articulos pedidos cliente 
	return Datatables::queryBuilder( DB::table('pedidoDet')
            ->join('lista', [['pedidoDet.codArticulo', '=', "lista.codArticulo"], ['pedidoDet.codProveedor', '=', "lista.codProveedor"]])
            ->select('pedidoDet.*', 'lista.descripcion') 
            ->where('idPedido', '=', $_POST['id'])  
    		)->make(true);

	//return Datatables::eloquent(App\PedidoDet::ArticulosPedidos($_POST['id']))->make(true);
});

//pedido Enc
Route::get('pedido/cerrarPedido/{idPedido}', ['uses' =>'PedidoController@cerrarPedido', 'as' => 'pedido.cerrarPedido']);
Route::get('pedido/anularPedido/{idPedido}', ['uses' =>'PedidoController@anularPedido', 'as' => 'pedido.anularPedido']);
Route::resource('pedido', 'PedidoController');
//pedido Det
Route::post('/pedidoDet/{idPedido}', ['uses' =>'PedidoDetController@store', 'as' => 'pedidoDet.store']);
Route::put('/pedidoDet/{idPedido}', ['uses' =>'PedidoDetController@update', 'as' => 'pedidoDet.update']);
Route::delete('/eliminarPedido/{idPedido}/{idDtalle}', ['uses' =>'PedidoDetController@destroy', 'as' => 'pedidoDet.destroy']);
Route::get('/detalle/{idPedido}', ['uses' =>'PedidoDetController@show', 'as' => 'pedidoDet.show']);

