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
		$role_inspector 	= Role::create(['name' => 'Inspector']);
    	$role_invitado			= Role::create(['name' => 'Invitado']);

		$permission = Permission::create(['name' => 'ver-usuarios']);
		$permission = Permission::create(['name' => 'configurar-usuarios']);
		
		$permission = Permission::create(['name' => 'crear-homenajes']); 			//C
		$permission = Permission::create(['name' => 'ver-homenajes']); 				//R
		$permission = Permission::create(['name' => 'editar-homenajes']); 		//U
		$permission = Permission::create(['name' => 'eliminar-homenajes']);	//D

		$permission = Permission::create(['name' => 'crear-rubros']); 			//C
		$permission = Permission::create(['name' => 'ver-rubros']); 			//R
		$permission = Permission::create(['name' => 'editar-rubros']); 		//U
		$permission = Permission::create(['name' => 'eliminar-rubros']); //D
		
		$permission = Permission::create(['name' => 'ver-destacados']);
		$permission = Permission::create(['name' => 'destacar-publicaciones']);
		$permission = Permission::create(['name' => 'aprobar-publicaciones']);
		$permission = Permission::create(['name' => 'eliminar-publicaciones']);
		
		$permission = Permission::create(['name' => 'crear-roles']);				//C
		$permission = Permission::create(['name' => 'ver-roles']);					//R
		$permission = Permission::create(['name' => 'editar-roles']);			//U
		$permission = Permission::create(['name' => 'eliminar-roles']);		//D
		$permission = Permission::create(['name' => 'asignar-permisos']);



		$list_permission = [ //for SuperAdmin 
			'ver-usuarios',
			'configurar-usuarios',
			'crear-homenajes',
			'ver-homenajes',
			'editar-homenajes',
			'eliminar-homenajes',
			'crear-rubros',
			'ver-rubros',
			'editar-rubros',
			'eliminar-rubros',
			'ver-destacados',
			'destacar-publicaciones',
			'aprobar-publicaciones',
			'eliminar-publicaciones',
			'crear-roles',
			'ver-roles',
			'editar-roles',
			'eliminar-roles',
			'asignar-permisos'
		];

		$list_permission2 = [ //for admin 
			'ver-usuarios',
			'configurar-usuarios',
			'crear-homenajes',
			'ver-homenajes',
			'editar-homenajes',
			'eliminar-homenajes',
			'ver-destacados',
			'destacar-publicaciones',
			'aprobar-publicaciones',
			'eliminar-publicaciones',
		];

		$list_permission3 = [ //for Supervisor 
			'ver-usuarios',
			'ver-destacados',
			'destacar-publicaciones',
			'aprobar-publicaciones',
			'eliminar-publicaciones',
		];

		$list_permission4 = [ //for Inspector  
			'ver-usuarios',
			'ver-homenajes',
			'ver-rubros',
			'ver-roles',
		];


		$role_superadmin->syncPermissions($list_permission);
		$role_admin->syncPermissions($list_permission2);
		$role_supervisor->syncPermissions($list_permission3);
		$role_inspector->syncPermissions($list_permission4);


		$admin = User::create([
			'name' => 'Rene Lara',
			'email' => 'usodev@usonsonate.edu.sv',
			'username' => 'social2020',
			'password'	=> Hash::make('s4FFDEvYwbixn'),
			'telephone' => '2222-2222',
			'status' => 'enabled',
		]);

		
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
