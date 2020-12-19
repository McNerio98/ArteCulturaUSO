<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class RolesPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$role_superadmin 	= Role::create(['name' => 'SuperAdmin']);
    	$role_admin 		= Role::create(['name' => 'Admin']);
    	$role_supervisor 	= Role::create(['name' => 'Supervisor']);
    	$role_autor			= Role::create(['name' => 'Autor']);
    	$role_autor			= Role::create(['name' => 'Invitado']);

		$permission = Permission::create(['name' => 'ver-usuarios']);
		$permission = Permission::create(['name' => 'aceptar-usuario']);
		$permission = Permission::create(['name' => 'desactivar-usuario']);
		$permission = Permission::create(['name' => 'activar-usuario']);
		$permission = Permission::create(['name' => 'crear-evento']);
		$permission = Permission::create(['name' => 'crear-expresion']);
		$permission = Permission::create(['name' => 'ver-categorias']);
		$permission = Permission::create(['name' => 'crear-categorias']);
		$permission = Permission::create(['name' => 'modificar-categorias']);
		$permission = Permission::create(['name' => 'eliminar-categorias']);
		$permission = Permission::create(['name' => 'crear-publicacion']);
		$permission = Permission::create(['name' => 'destacar-publicacion']);
		$permission = Permission::create(['name' => 'editar-publicacion']);

		$list_permission = [
			'ver-usuarios',
			'aceptar-usuario',
			'desactivar-usuario',
			'ver-categorias',
			'crear-categorias',
			'modificar-categorias',
			'eliminar-categorias',
			'activar-usuario'];

		$role_superadmin->syncPermissions($list_permission);


		//this is the default users with SuperAdmin role

		$admin = User::create([
			'name' => 'Rene Lara',
			'email' => 'usodev@usonsonate.edu.sv',
			'username' => 'social2020',
			'password'	=> Hash::make('s4FFDEvYwbixn'),
			'telephone' => '2222-2222',
			'status' => 'enabled',
		]);

		$admin->assignRole('SuperAdmin');

		
		// for testing 
		// IMPORTANT: This section most be deleted before deploy
		$invitado1 = User::create([
			'name' => 'Mario Nerio',
			'email' => 'ax.minck@gmail.com',
			'username' => 'mcnerio2020',
			'password'	=> Hash::make('123456789'),
			'telephone' => '2222-2222',
			'status' => 'enabled',
		]);

		$invitado1->assignRole('Invitado');


		$invitado2 = User::create([
			'name' => 'Alex Chinque',
			'email' => 'alexcnk97@gmail.com',
			'username' => 'cnk2020',
			'password'	=> Hash::make('123456789'),
			'telephone' => '2222-2222',
			'status' => 'request',
		]);

		$invitado2->assignRole('Invitado');			        
    }
}
