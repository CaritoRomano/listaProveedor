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
    	'slug' => 'admin',
    	'description' => '', // optional
    	'level' => 1, // optional, set to 1 by default
		]);

		Role::create([
    	'name' => 'Cliente',
    	'slug' => 'cliente',
    	'description' => '', // optional
    	'level' => 2, // optional, set to 1 by default
		]);
    }
}
