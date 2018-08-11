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
use App\ListaAnterior;
use Debugbar;
use org\majkel\dbase\Builder;
use org\majkel\dbase\Format;
use org\majkel\dbase\Field;
use org\majkel\dbase\Table;

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
            return view('admin.tablaListaArticulos');
        }else if (Auth::user()->hasRole('Cliente')) {  
            return redirect()->route('pedido.lista');
        }else{
            //return redirect()->route('pedido.lista');
            return redirect()->route('lista');
        }
    }

    public function actualizarLista(Request $request)
    {   
            if (Auth::user()->hasRole('Administrador')) {
            $archivo = $request->file('archivo');
            $nombreOriginal = $archivo->getClientOriginalName();
            $extension = $archivo->getClientOriginalExtension();
            if ($extension == 'csv'){
                $archivoEnDisco=Storage::disk('archivos')->put($nombreOriginal, \File::get($archivo));
                $url = storage_path('archivos')."/".$nombreOriginal;

                if($archivoEnDisco){
                    //paso lista a ListaAnterior para luego poder comparar precios
                    ListaAnterior::truncate();
                    $query1 = "INSERT INTO listaAnterior SELECT * FROM lista";
                    DB::connection()->getpdo()->exec($query1);
                    
                    //actualizo lista               
                    Lista::truncate();
                    //hoja 1 
                    $query = "LOAD DATA LOCAL INFILE '" . $url . "'
                        INTO TABLE lista
                        FIELDS TERMINATED BY ';' OPTIONALLY ENCLOSED BY '\"' ESCAPED BY '\"' LINES TERMINATED BY '\\n' IGNORE 1 LINES 
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
                    SET precio = REPLACE(@precio, ',', '.'),created_at=NOW(),updated_at=NOW()";
                    DB::connection()->getpdo()->exec($query);

                    //para exportar a Excel desde el cliente
                    $output = fopen(storage_path('archivos')."/RepuestosGonnet.csv", "w");
                    fputs($output, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
                    fputcsv($output, array('Cod. Fabrica', 'Fabrica', 'Cod. Articulo', 'Descripcion', 'Rubro', 'Importe'), ';');
                    $i=1;
                    $cantReg = 2000;
                    $limite=Lista::count();

                    $tableDBF = Builder::create()
                        ->setFormatType(Format::DBASE3)
                        ->addField(Field::create(Field::TYPE_CHARACTER)->setName('codFabrica')->setLength(3))
                        ->addField(Field::create(Field::TYPE_CHARACTER)->setName('fabrica')->setLength(60))
                        ->addField(Field::create(Field::TYPE_CHARACTER)->setName('codArt')->setLength(12))
                        ->addField(Field::create(Field::TYPE_CHARACTER)->setName('descrip')->setLength(250))
                        ->addField(Field::create(Field::TYPE_CHARACTER)->setName('rubro')->setLength(60))
                        ->addField(Field::create(Field::TYPE_CHARACTER)->setName('precio')->setLength(10))
                        ->build(storage_path('archivos')."/RepuestosGonnet.dbf");


                    while ($i < $limite) {
                        $results = Lista::select('codFabrica', 'fabrica', 'codArticulo', 'descripcion', 'rubro', 'precio')->whereBetween('id', [$i, $i+$cantReg])->get()->toArray();

                        foreach($results as $result){
                            fputcsv($output, $result, ';'); 
                        
                            $tableDBF->insert([
                                'codFabrica' => $result['codFabrica'],
                                'fabrica' => $result['fabrica'],  
                                'codArt' => $result['codArticulo'],
                                'descrip' => $result['descripcion'],
                                'rubro' => $result['rubro'],
                                'precio' => $result['precio'],  
                            ]);
                        }
                        $i=$i+$cantReg+1;
                    }   
                    fclose($output);

                                     
                    $viewMensCorrecto = view('mensajes.correcto', ['msj' => 'Lista actualizada correctamente.']);

                    $viewMensCorrectoRender = $viewMensCorrecto->renderSections();
                    return Response::json(['mensaje' => $viewMensCorrectoRender['mensajeCorrecto']]); 
                   
                }else{
                    $viewMensIncorrecto = view('mensajes.incorrecto', ['msj' => 'La lista no pudo ser actualizada.']);

                    $viewMensIncorrectoRender = $viewMensIncorrecto->renderSections();
                    return Response::json(['mensaje' => $viewMensIncorrectoRender['mensajeIncorrecto']]);
                }
            }else{
                $viewMensIncorrecto = view('mensajes.incorrecto', ['msj' => 'Debe cargar un archivo CSV.']);

                $viewMensIncorrectoRender = $viewMensIncorrecto->renderSections();
                    return Response::json(['mensaje' => $viewMensIncorrectoRender['mensajeIncorrecto']]);
            }    
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
