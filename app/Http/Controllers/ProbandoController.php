<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;


class ProbandoController extends Controller
{
    public function view(){ 
		$user = User::find(1); 
		$role = Role::find(2); 
        return view('admin.main');
    }
}
