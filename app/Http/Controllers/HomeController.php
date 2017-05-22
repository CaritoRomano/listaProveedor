<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lista;
use Storage;
use Excel;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articulosLista = Lista::orderBy('codProveedor', 'ASC')->paginate(50);
        return view('home', ['articulosLista' => $articulosLista]);
    }

    public function actualizarLista(Request $request)
    {   
        $archivo = $request->file('archivo');
        $nombreOriginal = $archivo->getClientOriginalName();
        $extension = $archivo->getClientOriginalExtension();
        if ($extension == 'csv'){
            $archivoEnDisco=Storage::disk('archivos')->put($nombreOriginal, \File::get($archivo));
            $url = storage_path('archivos')."/".$nombreOriginal;

            if($archivoEnDisco){
           
                Lista::truncate();
                //hoja 1 
                Excel::filter('chunk')->load($url)->chunk(250, function($hoja){
                //Excel::selectSheetsByIndex(0)->load($url, function($hoja){
                    //recorro la hoja, quedandome con las filas
                    $hoja->each(function($fila){
                        $articuloLista = new Lista;
                        $articuloLista->codProveedor = $fila->codproveedor;
                        $articuloLista->codArticulo = $fila->codarticulo;
                        $articuloLista->descripcion = $fila->descripcion;
                        $articuloLista->marca = $fila->marca;
                        $articuloLista->rubro = $fila->rubro;
                        $articuloLista->porcIva = $fila->porciva;
                        $articuloLista->precio = $fila->precio;
                        $articuloLista->save();
                    });
                });

                return view('mensajes.correcto', ['msj' => 'Lista actualizada correctamente.']);
            }else{
                return view('mensajes.incorrecto', ['msj' => "La lista no pudo ser actualizada."]);
            }
        }else{
            return view('mensajes.incorrecto', ['msj' => "Debe cargar un archivo CSV."]);
        }    
    }

}
