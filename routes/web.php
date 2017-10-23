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

/*
Route::get('/', ['uses' => 'Auth\LoginController@showLoginForm', 'as' => 'login'
]);*/
Route::get('/', ['uses' =>'InvitadoController@index', 'as' => 'index']);

Auth::routes();

Route::get('/home', ['uses' => 'HomeController@index', 'as' => 'home']);
Route::post('/actualizarLista', ['uses' => 'HomeController@actualizarLista', 'as' => 'actualizarLista']);

Route::post('/home/tabla', function(){  //datatable todos articulos cliente/admin
	return Datatables::eloquent(App\Lista::query())->make(true);
});

Route::post('/home/tablaPedidos', function(){ //datatable articulos pedidos 
	return Datatables::queryBuilder(DB::table('pedidoDet')
            ->join('lista', [['pedidoDet.codArticulo', '=', "lista.codArticulo"], ['pedidoDet.codFabrica', '=', "lista.codFabrica"]])
            ->select('pedidoDet.*', 'lista.precio as precio', 'lista.descripcion as descripcion', 'lista.fabrica as fabrica', DB::raw('(lista.precio*pedidoDet.cant) AS importe')) 
            ->where('idPedido', '=', $_POST['id'])  
    		)->make(true);
	//return Datatables::eloquent(App\PedidoDet::ArticulosPedidos($_POST['id']))->make(true);
});

Route::post('/pedido/recibir', function(){ //datatable recibir articulos 
	return Datatables::queryBuilder(DB::table('pedidoDet')
            ->join('lista', [['pedidoDet.codArticulo', '=', "lista.codArticulo"], ['pedidoDet.codFabrica', '=', "lista.codFabrica"]])
            ->select('pedidoDet.*', 'lista.descripcion as descripcion', 'lista.fabrica as fabrica', DB::raw('(pedidoDet.cant-pedidoDet.cantRecibida) AS cantFaltante'))
            ->where('idPedido', '=', $_POST['id'])
            ->whereRaw('pedidoDet.cant > pedidoDet.cantRecibida') 
    		)->make(true);
	//return Datatables::eloquent(App\PedidoDet::ArticulosPedidos($_POST['id']))->make(true);
});


//pedido Enc
Route::post('pedido/cerrarPedido/{idPedido}', ['uses' =>'PedidoController@cerrarPedido', 'as' => 'pedido.cerrarPedido']);
Route::get('pedido/reenviar/{idPedido}', ['uses' =>'PedidoController@reenviarPedido', 'as' => 'pedido.reenviarPedido']);
Route::get('pedido/enviarPedido/{idPedido}', ['uses' =>'PedidoController@enviarPedido', 'as' => 'pedido.enviarPedido']);
Route::get('pedido/anularPedido/{idPedido}', ['uses' =>'PedidoController@anularPedido', 'as' => 'pedido.anularPedido']);
Route::get('pedido/finalizarPedido/{idPedido}', ['uses' =>'PedidoController@finalizarPedido', 'as' => 'pedido.finalizarPedido']);
Route::get('pedido/recibir/{idPedido}', ['uses' =>'PedidoController@recibir', 'as' => 'pedido.recibir']);
Route::get('pedido/lista', ['uses' =>'PedidoController@lista', 'as' => 'pedido.lista']);
Route::post('pedido/recibirCant', ['uses' =>'PedidoController@recibirCant', 'as' => 'pedido.recibirCant']);
Route::post('/guardarObservaciones', ['uses' => 'PedidoController@guardarObservaciones', 'as' => 'guardarObservaciones']);
Route::resource('pedido', 'PedidoController');
//pedido Det
Route::post('/pedidoDet', ['uses' =>'PedidoDetController@store', 'as' => 'pedidoDet.store']);
Route::put('/pedidoDet', ['uses' =>'PedidoDetController@update', 'as' => 'pedidoDet.update']);
Route::delete('/eliminarPedido/{idPedido}/{idDetalle}', ['uses' =>'PedidoDetController@destroy', 'as' => 'pedidoDet.destroy']);
Route::get('/detalle/{idPedido}', ['uses' =>'PedidoDetController@show', 'as' => 'pedidoDet.show']);

Route::get('/register/verify/{code}', 'Auth\RegisterController@verifyEmail');
Route::get('user/password', ['uses' =>'HomeController@cambiarPassword', 'as' => 'home.cambiarPassword']);
Route::post('user/updatePassword', ['uses' =>'HomeController@updatePassword', 'as' => 'home.updatePassword']);

Route::resource('user', 'ClienteController');
Route::post('/clientes', function(){  //datatable listado clientes
    return Datatables::eloquent(App\User::query())->make(true);
});

Route::get('lista', ['uses' =>'InvitadoController@index', 'as' => 'index']);
Route::get('exportarListaCompleta', ['uses' =>'InvitadoController@exportarListaCompleta', 'as' => 'exportarListaCompleta']);