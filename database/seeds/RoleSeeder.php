<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
    	'name' => 'Administrador',
    	'display_name' => 'admin',
    	'description' => '', // optional
		]);

		Role::create([
    	'name' => 'Cliente',
    	'display_name'  => 'cliente',
    	'description' => '', // optional
		]);
    }
}
