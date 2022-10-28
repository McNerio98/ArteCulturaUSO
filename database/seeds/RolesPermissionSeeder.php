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

		$permission = Permission::create([
			'name' => 'ver-usuarios',
			'description' => 'Acceso al apartado donde están todos los usuarios, también puede ver la información completa de cada uno de ellos.'
		]);
		
		$permission = Permission::create([
			'name' => 'configurar-usuarios',
			'description' => 'Cambiar la información del usuario, así como habilitar, desactivar, o eliminar la cuenta, si tiene ese acceso podrá ver la contraseña del usuario, podrá aceptar un usuario.'
		]);
		
		$permission = Permission::create([
			'name' => 'crear-biografias',
			'description' => 'Capacidad para crear reseña de personajes, de tipo biografías u homenajes.'
		]);

		$permission = Permission::create([
			'name' => 'ver-biografias',
			'description' => 'Capacidad para ver biografías de personajes u homenajes, aprobados y los pendientes de aprobar, desde la página publica siempre serán visibles todos los homenajes aprobados.'
		]);

		$permission = Permission::create([
			'name' => 'editar-biografias',
			'description' => 'Capacidad de editar biografías u homenaje, cambiar sus datos e imágenes.'
		]);

		$permission = Permission::create([
			'name' => 'eliminar-biografias',
			'description' => 'Capacidad de eliminar biografías u homenajes.'
		]);

		$permission = Permission::create([
			'name' => 'crear-recursos',
			'description' => 'Capacidad de crear recursos, documentos PDF y videos.'
		]);
		
		$permission = Permission::create([
			'name' => 'ver-recursos',
			'description' => 'Capacidad de ver el apartado de recursos, documentos PDF y videos.'
		]);
		
		$permission = Permission::create([
			'name' => 'editar-recursos',
			'description' => 'Capacidad de editar recursos.'
		]);

		$permission = Permission::create([
			'name' => 'eliminar-recursos',
			'description' => 'Capacidad de eliminar un ítem de tipo recurso, documentos PDF y videos, si el recurso fue tomado de una publicación o evento; aun seguirá existiendo en ese elemento.'
		]);


		$permission = Permission::create([
			'name' => 'crear-rubros',
			'description' => 'Puede crear categorías como etiquetas.'
		]);

		$permission = Permission::create([
			'name' => 'ver-rubros',
			'description' => 'Puede ver el apartado de rubros, donde se incluyen categorías y etiquetas asociadas.'
		]);

		$permission = Permission::create([
			'name' => 'editar-rubros',
			'description' => 'Capacidad de editar categorías de rubros, y etiquetas asociadas.'
		]);

		$permission = Permission::create([
			'name' => 'eliminar-rubros',
			'description' => 'Capacidad de eliminar categorías de rubros y etiquetas asociadas.'
		]);

		$permission = Permission::create([
			'name' => 'aprobar-publicaciones',
			'description' => 'Capacidad de aprobar/desaprobar una publicación o evento.'
		]);

		$permission = Permission::create([
			'name' => 'editar-publicaciones',
			'description' => 'Capacidad de editar publicaciones/eventos. El usuario propietario (creador original) siempre podrá editar, si edita se establecerá a estado de revisión nuevamente.'
		]);

		$permission = Permission::create([
			'name' => 'eliminar-publicaciones',
			'description' => 'Capacidad de eliminar publicaciones/eventos. El usuario propietario (creador original) siempre podrá eliminarlas.'
		]);
		
		$permission = Permission::create([
			'name' => 'crear-roles',
			'description' => 'Capacidad de crear roles'
		]);

		$permission = Permission::create([
			'name' => 'ver-roles',
			'description' => 'Capacidad de visualización del panel de Roles y permisos'
		]);

		$permission = Permission::create([
			'name' => 'editar-roles',
			'description' => 'Capacidad de modificar roles'
		]);

		$permission = Permission::create([
			'name' => 'eliminar-roles',
			'description' => 'Capacidad de eliminar roles' //verificar aqui que pasa con los usuarios que lo tienen 
		]);

		$permission = Permission::create([
			'name' => 'asignar-permisos',
			'description' => 'Capacidad de asignar N permisos a un rol determinado '
		]);

		$permission = Permission::create([
			'name' => 'crear-promociones',
			'description' => 'Capacidad para crear promocionales'
		]);

		$permission = Permission::create([
			'name' => 'editar-promociones',
			'description' => 'Capacidad para editar promocionales'
		]);
		
		$permission = Permission::create([
			'name' => 'eliminar-promociones',
			'description' => 'Capacidad para eliminar promocionales'
		]);		


		$permission = Permission::create([
			'name' => 'ver-procesos',
			'description' => 'Capacidad para visualizar los procesos administrativos'
		]);
		
		$permission = Permission::create([
			'name' => 'ejecutar-procesos',
			'description' => 'Capacidad para ejecutar procesos administrativos '
		]);		
		



		$list_permission = [ //for SuperAdmin 
			'ver-usuarios',
			'configurar-usuarios',
			'crear-biografias',
			'ver-biografias',
			'editar-biografias',
			'eliminar-biografias',
			'crear-recursos',
			'ver-recursos',
			'editar-recursos',
			'eliminar-recursos',
			'crear-rubros',
			'ver-rubros',
			'editar-rubros',
			'eliminar-rubros',
			'aprobar-publicaciones',
			'editar-publicaciones',
			'eliminar-publicaciones',
			'crear-roles',
			'ver-roles',
			'editar-roles',
			'eliminar-roles',
			'asignar-permisos',
			'crear-promociones',
			'editar-promociones',
			'eliminar-promociones',
			'ver-procesos',
			'ejecutar-procesos'
		];

		$list_permission2 = [ //for admin 
			'ver-usuarios',
			'configurar-usuarios',
			'crear-biografias',
			'ver-biografias',
			'editar-biografias',
			'eliminar-biografias',
			'crear-recursos',
			'ver-recursos',
			'editar-recursos',
			'eliminar-recursos',
			'aprobar-publicaciones',
			'editar-publicaciones',
			'eliminar-publicaciones',
			'ver-roles'
		];

		$list_permission3 = [ //for Supervisor 
			'ver-usuarios',
			'aprobar-publicaciones',
			'editar-publicaciones',
			'eliminar-publicaciones',
			'ver-roles'
		];

		$list_permission4 = [ //for Inspector  
			'ver-usuarios',
			'ver-biografias',
			'ver-recursos',
			'ver-rubros'
		];


		$role_superadmin->syncPermissions($list_permission);
		$role_admin->syncPermissions($list_permission2);
		$role_supervisor->syncPermissions($list_permission3);
		$role_inspector->syncPermissions($list_permission4);


		$admin = User::create([
			'name' => 'Rene Lara',
			'artistic_name' => 'Rene Lara',
			'email' => 'rene.lara@usonsonate.edu.sv',
			'username' => 'social2020',
			'email_verified_at' => '2020-05-31 17:26:21',
			'password'	=> Hash::make('s4FFDEvYwbixn'),
			'is_admin' => true,
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
    }
}
