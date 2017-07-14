<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use Excel;
use DB;
use Response;
use Auth;
use App\Lista;
use Hash;
use Validator;
use App\User;

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
    public function index(Request $request)
    {
        if (Auth::user()->hasRole('Administrador')) {
            $articulosLista = Lista::descripcion($request->get('descrip'))->orderBy('codArticulo', 'DESC')->paginate(50);
            return view('admin.tablaListaArticulos', ['articulosLista' => $articulosLista]);
        }else if (Auth::user()->hasRole('Cliente')) {  
            /*$articulosLista = Lista::descripcion($request->get('descrip'))->orderBy('codArticulo', 'DESC')->paginate(250);
            return view('layouts.app', ['articulosLista' => $articulosLista]);*/
            return redirect()->route('pedido.index');
        }
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
                $query = "LOAD DATA LOCAL INFILE '" . $url . "'
                    INTO TABLE lista
                    FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' ESCAPED BY '\"' LINES TERMINATED BY '\\n' IGNORE 1 LINES 
                        (
                        codarticulo,
                        rubro,
                        fabrica,
                        descripcion,
                        @dummy,
                        @dummy,
                        @dummy,
                        @precio,
                        @dummy,
                        @dummy,
                        codfabrica,                        
                        @dummy,
                        @dummy,
                        @dummy,
                        @dummy,
                        @dummy,
                        @dummy,
                        @dummy,
                        @dummy,
                        @created_at,
                        @updated_at)
                SET precio = REPLACE(@precio, ',', '.'),created_at=NOW(),updated_at=null";
                DB::connection()->getpdo()->exec($query);
                
                $viewMensCorrecto = view('mensajes.correcto', ['msj' => 'Lista actualizada correctamente.']);

                $viewMensCorrectoRender = $viewMensCorrecto->renderSections();
                return Response::json(['mensaje' => $viewMensCorrectoRender['mensajeCorrecto']]); 
               
            }else{
                return view('mensajes.incorrecto', ['msj' => "La lista no pudo ser actualizada."]);
            }
        }else{
            return view('mensajes.incorrecto', ['msj' => "Debe cargar un archivo CSV."]);
        }    
    }

    /*CAMBIAR CONTRASEÑA USUARIOS ADMIN Y CLIENTE*/
    public function cambiarPassword(){
        return view('user.cambioPassword');
    }

    public function updatePassword(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|min:6|confirmed',
        ]);
       
        if($validator->fails()){
            return view('user.cambioPassword')->withErrors($validator);
        }else{
            //comprueba si el password es correcto
            if ((Hash::check($request->passwordActual, Auth::user()->password))
                && ($request->email == Auth::user()->email)){
                $user = User::find(Auth::id());
                $user->password = bcrypt($request->password);
                $user->save();
                return redirect('/home');
            }else{
                return view('user.cambioPassword')->with('mensaje', "Contrase&ntilde;a actual incorrecta.");
            }
        }
    }
    /*FIN CAMBIAR CONTRASEÑA USUARIOS ADMIN Y CLIENTE*/


    //ACTUALIZA LOS DATOS
    /*public function actualizarLista(Request $request)
    {   
        $archivo = $request->file('archivo');
        $nombreOriginal = $archivo->getClientOriginalName();
        $extension = $archivo->getClientOriginalExtension();
        if ($extension == 'csv'){
            $archivoEnDisco=Storage::disk('archivos')->put($nombreOriginal, \File::get($archivo));
            $url = storage_path('archivos')."/".$nombreOriginal;

            if($archivoEnDisco){
           
                //hoja 1 
                Excel::filter('chunk')->load($url)->chunk(250, function($hoja){
                //Excel::selectSheetsByIndex(0)->load($url, function($hoja){
                    //recorro la hoja, quedandome con las filas
                    $hoja->each(function($fila){
                         set_time_limit(50);
                        $articulo = Lista::codigos($fila->codproveedor, $fila->codarticulo)->get();//devuelve un array
                        if(sizeof($articulo) > 0){ //existe el articulo
                            foreach($articulo as $artGuardado){   //si cambio el precio, lo modifico
                                if ($artGuardado->precio != $fila->precio){
                                    $artGuardado->precio = $fila->precio;
                                    $artGuardado->save(); 
                                }
                            }
                        }else{         //no existe, lo agrego 
                            $articuloLista = new Lista;
                            $articuloLista->codProveedor = $fila->codproveedor;
                            $articuloLista->codArticulo = $fila->codarticulo;
                            $articuloLista->descripcion = $fila->descripcion;
                            $articuloLista->marca = $fila->marca;
                            $articuloLista->rubro = $fila->rubro;
                            $articuloLista->porcIva = $fila->porciva;
                            $articuloLista->precio = $fila->precio;
                            $articuloLista->save();
                        }
                    });
                });

                return view('mensajes.correcto', ['msj' => 'Lista actualizada correctamente.']);
            }else{
                return view('mensajes.incorrecto', ['msj' => "La lista no pudo ser actualizada."]);
            }
        }else{
            return view('mensajes.incorrecto', ['msj' => "Debe cargar un archivo CSV."]);
        }    
    }*/

    //ELIMINA Y CARGA TODO
    /*public function actualizarLista(Request $request)
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
    }*/

}
