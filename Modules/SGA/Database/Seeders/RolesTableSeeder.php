<?php

namespace Modules\SGA\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\SICA\Entities\App;
use Modules\SICA\Entities\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Consultar aplicación SICA para registrar los roles
        $app = App::where('name', 'SGA')->firstOrFail();

        // Consultar rol  de superadministrador
        $rol_superadmin = Role::where('slug', 'superadmin')->firstOrFail();

        // Registrar o actualizar rol de ADMINISTRADOR
        $rol_admin = Role::updateOrCreate(['slug' => 'sga.admin'], [
            'name' => 'Administrador',
            'description' => 'Rol administrador de la aplicación SGA',
            'description_english' => 'SGA application administrator role',
            'full_access' => 'no',
            'app_id' => $app->id
        ]);

        // Registrar o actualizar rol de FUNCIONARIO
        $rol_staff = Role::updateOrCreate(['slug' => 'sga.staff'], [
            'name' => 'Funcionario',
            'description' => 'Rol funcionario de la aplicación SGA',
            'description_english' => 'SGA application staff role',
            'full_access' => 'No',
            'app_id' => $app->id
        ]);

        // Registrar o actualizar rol de APRENDIZ
        $rol_apprentice = Role::updateOrCreate(['slug' => 'sga.apprentice'], [
            'name' => 'Aprendiz',
            'description' => 'Rol aprendiz de la aplicación SGA',
            'description_english' => 'SGA application apprentice role',
            'full_access' => 'No',
            'app_id' => $app->id
        ]);

        // Consulta de usuarios
        $user_admin = User::where('nickname', 'BAROD461')->firstOrFail(); // Usuario Administrador
        $user_staff = User::where('nickname', 'RCCER036')->firstOrFail(); // Usuario Funcionario
        $user_apprentice = User::where('nickname', 'LFMON175')->firstOrFail(); // Usuario Aprendiz
        $user_superadmin = User::where('nickname', 'EEEEE000')->firstOrFail(); // Usuario Super Administrador

        // Asignación de ROLES para los USUARIOS de la aplicación SGA (Sincronización de las relaciones sin eliminar las relaciones existentes)
        $user_admin->roles()->syncWithoutDetaching([$rol_admin->id]);
        $user_staff->roles()->syncWithoutDetaching([$rol_staff->id]);
        $user_apprentice->roles()->syncWithoutDetaching([$rol_apprentice->id]);
        $user_superadmin->roles()->syncWithoutDetaching([$rol_superadmin->id]);
    }
}
