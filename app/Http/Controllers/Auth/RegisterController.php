<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Mail;
use App\Mail\NuevoUsuarioEmail;
use App\Role;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/register';

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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'codCliente' => 'required|unique:users',
           // 'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $password = $data['codCliente'];
        $data['confirmation_code'] = str_random(25);
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'codCliente' => $data['codCliente'],
            'password' => bcrypt($password),
            'confirmation_code' => $data['confirmation_code']
        ]);
 
        $role = Role::find(2); /*cliente*/
        $user->attachRole($role);

        return $user;
    }


    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request) /*sobreescribe inicio sesion*/
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
        $user = $this->create($request->all());
        
        //Mail::to('pruebasmailsweb@gmail.com')
        Mail::to($user->email)
                    ->send(new NuevoUsuarioEmail($user));            

        return view('auth.register', ['email' => $user->email ]);
    }

    //para verificar email al registrarse
    public function verifyEmail($code)
    {
        $user = User::where('confirmation_code', $code)->first();

        if (! $user)
            return redirect('/');

        $user->confirmed = true;
        $user->confirmation_code = null;
        $user->save();

        $this->guard()->login($user);

        return redirect('/login')->with('notification', 'Has confirmado correctamente tu correo!');
    }

}
