<?php

namespace Modules\SGA\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\SICA\Entities\App;
use Modules\SICA\Entities\Permission;
use Modules\SICA\Entities\Role;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions_admin = [];
        $permissions_staff = [];
        $permissions_apprentice = [];

        // Obtener la app SGA
        $app = App::where('name', 'SGA')->first();

        // Crear permisos Administrativos
        $permission = Permission::updateOrCreate(['slug' => 'sga.admin.index'], [
            'name' => 'Acceso al Rol de Administrador',
            'description' => 'Acceso al Rol de Administrador SGA',
            'description_english' => 'Access to the SGA Administrator Role',
            'app_id' => $app->id
        ]);
        $permissions_admin[] = $permission->id;

        // CRUD de Funcionarios (Administrador) 
        $permission = Permission::updateOrCreate(['slug' => 'sga.admin.staff'], [
            'name' => 'Acceso a editar funcionarios dentro de SGA',
            'description' => 'Acceso a CRUD de Funcionarios de SGA',
            'description_english' => 'Access to SGA Staff CRUD',
            'app_id' => $app->id
        ]);
        $permissions_admin[] = $permission->id;

        $permission = Permission::updateOrCreate(['slug' => 'sga.admin.staff.users'], [
            'name' => 'Acceso a consultar usuarios de funcionarios dentro de SGA',
            'description' => 'Acceso a consultar usuarios de Funcionarios de SGA',
            'description_english' => 'Access to View SGA Staff Users',
            'app_id' => $app->id
        ]);
        $permissions_admin[] = $permission->id;

        
        $permission = Permission::updateOrCreate(['slug' => 'sga.admin.staff.users.show'], [
            'name' => 'Acceso a consultar información de usuario de funcionario dentro de SGA',
            'description' => 'Acceso a consultar información de usuario de Funcionarios de SGA',
            'description_english' => 'Access to View SGA Staff User Information',
            'app_id' => $app->id
        ]);
        $permissions_admin[] = $permission->id;

        $permission = Permission::updateOrCreate(['slug' => 'sga.admin.staff.users.update-password'], [
            'name' => 'Acceso a cambiar contraseña de usuario de funcionario dentro de SGA',
            'description' => 'Acceso a cambiar contraseña de usuario de Funcionarios de SGA',
            'description_english' => 'Access to Change SGA Staff User Password',
            'app_id' => $app->id
        ]);
        $permissions_admin[] = $permission->id;
        // CRUD de Aprendices (Administrador)
        $permission = Permission::updateOrCreate(['slug' => 'sga.admin.apprentice'], [
            'name' => 'Acceso a editar aprendices dentro de SGA',
            'description' => 'Acceso a CRUD de Aprendices de SGA',
            'description_english' => 'Access to SGA Apprentice CRUD',
            'app_id' => $app->id
        ]);
        $permissions_admin[] = $permission->id;

        // Consultar asistencias de aprendices (Administrador)
        $permission = Permission::updateOrCreate(['slug' => 'sga.admin.asv'], [
            'name' => 'Acceso a consultar asistencias de los aprendices dentro de SGA',
            'description' => 'Acceso a leer asistencias de Aprendices de SGA',
            'description_english' => 'Access to read Apprentice Attendance in SGA',
            'app_id' => $app->id
        ]);
        $permissions_admin[] = $permission->id;

        $permission = Permission::updateOrCreate(['slug' => 'sga.admin.asv.asistencias.jornada'], [
            'name' => 'Acceso a consultar asistencias de los aprendices dentro de SGA',
            'description' => 'Acceso a filtrar asistencias de Aprendices de SGA',
            'description_english' => 'Access to filter Apprentice Attendance in SGA',
            'app_id' => $app->id
        ]);
        $permissions_admin[] = $permission->id;

        // Editar puntaje de convocatoria alimentaria (Administrador)
        $permission = Permission::updateOrCreate(['slug' => 'sga.admin.s-assgn'], [
            'name' => 'Acceso a editar puntaje de convocatoria alimentaria de SGA',
            'description' => 'Acceso a CRU de puntajes de convocatoria de SGA',
            'description_english' => 'Access to SGA Evaluation Score Management (CRU)',
            'app_id' => $app->id
        ]);
        $permissions_admin[] = $permission->id;

        $permission = Permission::updateOrCreate(['slug' => 'sga.admin.ev-history'], [
            'name' => 'Acceso a visualizar historial de los puntajes de convocatorias anteriores alimentarias de SGA',
            'description' => 'Acceso a R historial de puntajes de convocatoria de SGA',
            'description_english' => 'Access to Historical Scores of SGA Calls (Read Permission)',
            'app_id' => $app->id
        ]);
        $permissions_admin[] = $permission->id;

        $permission = Permission::updateOrCreate(['slug' => 'sga.admin.b-summary'], [
            'name' => 'Acceso a visualizar los puntajes de los aprendices en la convocatoria alimentaria actual de SGA',
            'description' => 'Acceso a R puntajes de los aprendices en la convocatoria de SGA',
            'description_english' => 'Access to View Apprentice Scores for the SGA Call',
            'app_id' => $app->id
        ]);
        $permissions_admin[] = $permission->id;

        $permission = Permission::updateOrCreate(['slug' => 'sga.admin.exp-data'], [
            'name' => 'Acceso a exportar información de informes de SGA',
            'description' => 'Acceso a exportar información de informes de SGA e información de los aprendices "Formato Socioeconómico"',
            'description_english' => 'Access to Export SGA Reports and Apprentice Socioeconomic Form Data',
            'app_id' => $app->id
        ]);
        $permissions_admin[] = $permission->id;

        $permission = Permission::updateOrCreate(['slug' => 'sga.admin.sys-params'], [
            'name' => 'Acceso configurar entorno de SGA',
            'description' => 'Acceso a configurar convocatorias, puntajes e informes de inasistencias de SGA',
            'description_english' => 'Access to Configure SGA Application Calls, Evaluation Scores, and Absence Reports',
            'app_id' => $app->id
        ]);
        $permissions_admin[] = $permission->id;


        // Permisos de perfil de usuario (Administrador)
        $permission = Permission::updateOrCreate(['slug' => 'sga.admin.profile'], [
            'name' => 'Acceso configurar perfil de usuario de SGA',
            'description' => 'Acceso a cambiar información personal del perfil actual de SGA',
            'description_english' => 'Access to Modify Personal Data of Current SGA User Profile',
            'app_id' => $app->id
        ]);
        $permissions_admin[] = $permission->id;

        $permission = Permission::updateOrCreate(['slug' => 'sga.admin.profile.change-password'], [
            'name' => 'Acceso de cambiar contraseña del perfil de administrador de SGA',
            'description' => 'Acceso a cambiar contraseña personal del perfil actual de SGA',
            'description_english' => 'Access to Modify Personal Password of Current SGA User Profile',
            'app_id' => $app->id
        ]);
        $permissions_admin[] = $permission->id;

        $permission = Permission::updateOrCreate(['slug' => 'sga.admin.profile.update-personal-info'], [
            'name' => 'Acceso de cambiar información personal de administrador de SGA',
            'description' => 'Acceso a cambiar infromación completa personal del perfil actual de SGA',
            'description_english' => 'Access to Modify Complete Personal Data of Current SGA User Profile',
            'app_id' => $app->id
        ]);
        $permissions_admin[] = $permission->id;

        // Crear permisos Funcionarios
        $permission = Permission::updateOrCreate(['slug' => 'sga.staff.index'], [
            'name' => 'Acceso al Rol de Funcionario',
            'description' => 'Acceso al Rol de Funcionario SGA',
            'description_english' => 'Access to the SGA Staff Role',
            'app_id' => $app->id
        ]);
        $permissions_staff[] = $permission->id;

        $permission = Permission::updateOrCreate(['slug' => 'sga.staff.ops-reports'], [
            'name' => 'Acceso a Reportes Operativos de SGA',
            'description' => 'Acceso consultar asistencias y beneficiarios de SGA',
            'description_english' => 'Access to Consult SGA Attendance and Beneficiary Data',
            'app_id' => $app->id
        ]);
        $permissions_staff[] = $permission->id;

        $permission = Permission::updateOrCreate(['slug' => 'sga.staff.rec-validation'], [
            'name' => 'Acceso a registrar asistencias de Aprendices de SGA',
            'description' => 'Acceso a registrar asistencias y manejar incidencias de Aprendices de SGA',
            'description_english' => 'Access to Attendance Registration and Incident Management for SGA Apprentices',
            'app_id' => $app->id
        ]);
        $permissions_staff[] = $permission->id;

        $permission = Permission::updateOrCreate(['slug' => 'sga.staff.profile'], [
            'name' => 'Acceso configurar perfil de usuario de SGA',
            'description' => 'Acceso a cambiar información personal del perfil actual de SGA',
            'description_english' => 'Access to Modify Personal Data of Current SGA User Profile',
            'app_id' => $app->id
        ]);
        $permissions_staff[] = $permission->id;

        // Crear permisos Aprendices
        $permission = Permission::updateOrCreate(['slug' => 'sga.apprentice.index'], [
            'name' => 'Acceso al Rol de Aprendiz',
            'description' => 'Acceso al Rol de Aprendiz SGA',
            'description_english' => 'Access to the SGA Apprentice Role',
            'app_id' => $app->id
        ]);
        $permissions_apprentice[] = $permission->id;

        $permission = Permission::updateOrCreate(['slug' => 'sga.apprentice.my-benefit'], [
            'name' => 'Acceso a consultar mi beneficio',
            'description' => 'Vista personalizada donde el usuario puede consultar la información de su beneficio actual.',
            'description_english' => 'Personalized view where the user can consult information about their current benefit.',
            'app_id' => $app->id
        ]);
        $permissions_apprentice[] = $permission->id;

        $permission = Permission::updateOrCreate(['slug' => 'sga.apprentice.ben-history'], [
            'name' => 'Acceso a consultar mi historial de beneficios',
            'description' => 'Registro detallado de todos los beneficios recibidos o asignados a un usuario a lo largo del tiempo.',
            'description_english' => 'Detailed record of all benefits received or assigned to a user over time.',
            'app_id' => $app->id
        ]);
        $permissions_apprentice[] = $permission->id;

        $permission = Permission::updateOrCreate(['slug' => 'sga.apprentice.apply-to-call'], [
            'name' => 'Acceso a enviar solicitud a convocatoria',
            'description' => 'Funcionalidad que permite a los usuarios enviar su solicitud para participar en una convocatoria o proceso de selección.',
            'description_english' => 'Feature that allows users to submit their application to participate in a recruitment or selection call.',
            'app_id' => $app->id
        ]);
        $permissions_apprentice[] = $permission->id;

        $permission = Permission::updateOrCreate(['slug' => 'sga.apprentice.profile'], [
            'name' => 'Acceso configurar perfil de usuario de SGA',
            'description' => 'Acceso a cambiar información personal del perfil actual de SGA',
            'description_english' => 'Access to Modify Personal Data of Current SGA User Profile',
            'app_id' => $app->id
        ]);
        $permissions_apprentice[] = $permission->id;

        // Consulta de ROLES
        $rol_admin = Role::where('slug', 'sga.admin')->first(); // Rol Administrador
        $rol_staff = Role::where('slug', 'sga.staff')->first(); // Rol Funcionario
        $rol_apprentice = Role::where('slug', 'sga.apprentice')->first(); // Rol Aprendiz

        // Asociar permisos
        $rol_admin->permissions()->syncWithoutDetaching($permissions_admin);
        $rol_staff->permissions()->syncWithoutDetaching($permissions_staff);
        $rol_apprentice->permissions()->syncWithoutDetaching($permissions_apprentice);
    }
}