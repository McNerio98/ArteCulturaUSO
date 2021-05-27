<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
use App\MediaProfile;

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
		$permission = Permission::create(['name' => 'crear-evento']);
		$permission = Permission::create(['name' => 'crear-expresion']);
		$permission = Permission::create(['name' => 'ver-categorias']);
		$permission = Permission::create(['name' => 'crear-categorias']);
		$permission = Permission::create(['name' => 'modificar-categorias']);
		$permission = Permission::create(['name' => 'eliminar-categorias']);
		$permission = Permission::create(['name' => 'crear-publicacion']);
		$permission = Permission::create(['name' => 'destacar-publicacion']);
		$permission = Permission::create(['name' => 'editar-publicacion']);

		$permission = Permission::create(['name' => 'configurar-usuario']);



		$list_permission = [
			'configurar-usuario',
			'ver-usuarios',
			'ver-categorias',
			'crear-categorias',
			'modificar-categorias',
			'eliminar-categorias'];

		$role_superadmin->syncPermissions($list_permission);



		$admin = User::create([
			'name' => 'Rene Lara',
			'email' => 'usodev@usonsonate.edu.sv',
			'username' => 'social2020',
			'password'	=> Hash::make('s4FFDEvYwbixn'),
			'telephone' => '2222-2222',
			'status' => 'enabled',
		]);

		//hacer esto para todos los demas 
		$profile0 = MediaProfile::create([
			'user_id' => $admin->id,
			'path_file' => 'default_img_profile.png'
		]);		
		$admin->img_profile_id = $profile0->id;
		$admin->save();
		//this is the default users with SuperAdmin role
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
		$profile1 = MediaProfile::create([
			'user_id' => $invitado1->id,
			'path_file' => 'default_img_profile.png'
		]);			
		$invitado1->img_profile_id = $profile1->id;
		$invitado1->save();
		$invitado1->assignRole('Invitado');

		
		$invitado2 = User::create([
			'name' => 'Alex Chinque',
			'email' => 'alexcnk97@gmail.com',
			'username' => 'cnk2020',
			'password'	=> Hash::make('123456789'),
			'telephone' => '2222-2222',
			'status' => 'request',
		]);
		$profile2 = MediaProfile::create([
			'user_id' => $invitado2->id,
			'path_file' => 'default_img_profile.png'
		]);			
		$invitado2->img_profile_id = $profile2->id;
		$invitado2->save();
		$invitado2->assignRole('Invitado');			        
    }
}
